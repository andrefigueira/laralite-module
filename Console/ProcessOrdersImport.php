<?php

namespace Modules\Laralite\Console;

use Illuminate\Console\Command;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Models\ImportedOrder;
use Modules\Laralite\Models\Order;
use Modules\Laralite\Models\Product;
use Modules\Laralite\Models\TempCsvData;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', '0');
class ProcessOrdersImport extends Command
{
    public $skuMap = [
        'TRPMUSEUMA' => 'TRAPMUSICTICKET',
        'SQ4865302' => 'SQ4865302',
    ];

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'laralite:import-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import orders from temporary table.';

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $tempRows = TempCsvData::all();
        $orders = 0;
        $customers = 0;
        $skipped = [];

        if ($tempRows->isEmpty()) {
            $this->error('No rows in temporary table. Please import a CSV file.');
        }

        foreach ($tempRows as $tempRow)
        {
            $basket = [];
            $refunded = false;
            $order = ImportedOrder::firstWhere(['ext_order_id' => $tempRow['order_id']]);
            $orderStatus = 'unknown';

            if ($order) {
                continue;
            }

            try {
                $customer = Customer::where('email', '=', $tempRow->email)->firstOrFail();
                $this->info('Found customer ' . $tempRow->email);
            } catch (\Throwable $exception) {
                $this->info('Creating customer with email ' . $tempRow->email);
                $customer = Customer::create([
                    'unique_id' => Uuid::uuid4(),
                    'name' => $tempRow->billing_name,
                    'email' => $tempRow->email,
                ]);

                $customers++;
            }


            try {
                // Find product relating to order
                $newSku = $this->skuMap[$tempRow->lineitem_sku];
                $fetchedProduct = Product::whereJsonContains('variants', ['sku' => $newSku])->firstOrFail();
                $this->info('Found product with SKU code ' . $newSku);
            } catch (\Throwable $exception) {
                // No product found ...
                // Log and skip for now...
                $skipped[] = $tempRow;
                $this->warn('Could not find product with SKU code ' . $tempRow->lineitem_sku);
                $this->warn('Skipping order with id ' . $tempRow->order_id);
                \Log::info('Skipping CSV row import as no product found', [
                    'requested_sku' => $tempRow->lineitem_sku,
                    'skipped_row' => $tempRow,
                ]);

                continue;
            }

            $productSku = $newSku ?: '';
            $productSlug = $fetchedProduct->slug ?: '';
            $productImage = str_replace('\\', '', $fetchedProduct->images[0]) ?: '';
            $productPrice = !empty($tempRow->lineitem_price) ? (int)($tempRow->lineitem_price * 100) : '';
            $productQuantity = (int)$tempRow->lineitem_quantity ?: 0;

            // Build basket object
            $basket['products'][] = [
                'sku' => $productSku,
                'slug' => $productSlug,
                'image' => $productImage,
                'price' => $productPrice,
                'quantity' => $productQuantity,
            ];
            if (!empty($tempRow->discount_code)) {
                $basket['discounts'][0]['code'] = $tempRow->discount_code;
                $basket['discounts'][0]['name'] = $tempRow->discount_code;
            }
            $basket['discountAmount'] = !empty($tempRow->discount_amount) ? (int)($tempRow->discount_amount * 100) : 0;
            $basket['taxAmount'] = !empty($tempRow->taxes) ? (int)($tempRow->taxes * 100) : 0;
            $basket['total'] = !empty($tempRow->total) ? (int)($tempRow->total * 100) : 0;

            // Build payment result object
            $result = [];
            $result['id'] = $tempRow->payment_reference ?: '';

            if ($tempRow->financial_status === 'PAID') {
                $result['paid'] = true;
                $orderStatus = 'complete';

                if ($tempRow->fulfillment_status === 'FULFILLED') {
                    $orderStatus = 'fulfilled';
                }
            }

            if ($tempRow->financial_status === 'refunded') {
                $result['paid'] = true;
                $refunded = true;
                $orderStatus = 'refunded';
            }

            $result['amount'] = $basket['total'];
            $result['source'] = [
                'name' => $tempRow->billing_name ?: '',
                'address_city' => $tempRow->billing_city ?: '',
                'address_line1' => $tempRow->billing_address_line_1 ?: '',
                'address_line2' => $tempRow->billing_address_line_2 ?: '',
                'address_country' => $tempRow->billing_country ?: '',
            ];
            $result['currency'] = $tempRow->currency ?: '';
            $result['description'] = 'TrapMusicMuseum Payment';
            $result['receipt_email'] = $tempRow->email;
            $result['billing_details'] = [
                'name' => $tempRow->billing_name ?: '',
                'email' => $tempRow->email,
                'phone' => $tempRow->billing_phone ?: '',
                'address' => [
                    'city' => $tempRow->billing_city ?: '',
                    'line1' => $tempRow->billing_address_line_1 ?: '',
                    'line2' => $tempRow->billing_address_line_2 ?: '',
                    'state' => $tempRow->billing_province ?: '',
                    'country' => $tempRow->billing_country ?: '',
                    'postal_code' => $tempRow->billing_zip ?: '',
                ],
            ];
            $result['payment_method_details'] = [
                'payment_method' => $tempRow->payment_method ?: '',
            ];
            $result['channel_name'] = $tempRow->channel_name ?: '';

            $createdDate = new \DateTime($tempRow->created_at);
            $timeZone = new \DateTimeZone('UTC');
            $createdDate->setTimezone($timeZone);

            try {
                $insertedOrder = Order::create([
                    'unique_id' => Uuid::uuid4(),
                    'customer_id' => $customer->id,
                    'basket' => $basket,
                    'payment_processor_result' => $result,
                    'status' => 1,
                    'refunded' => $refunded,
                    'order_status' => $orderStatus,
                    'created_at' => $createdDate,
                    'updated_at' => $createdDate,
                ]);
            } catch (\Throwable $e){
                $this->error(
                    'Error occurred during order creation from import of order ID '
                    . $tempRow['order_id'] . ': ' .  $e->getMessage()
                );
                continue;
            }

            $this->info('order id ' . $tempRow['order_id'] . ' imported successfully!');
            $this->info($orders . ' imported!');
            $importedOrder = new ImportedOrder(['ext_order_id' => $tempRow['order_id'], 'order_id' => $insertedOrder->unique_id]);
            $importedOrder->save();
            $orders++;
        }

        // Clear the temporary table
        TempCsvData::truncate();
    }

//    /**
//     * Get the console command arguments.
//     *
//     * @return array
//     */
//    protected function getArguments()
//    {
//        return [
//            ['example', InputArgument::REQUIRED, 'An example argument.'],
//        ];
//    }

//    /**
//     * Get the console command options.
//     *
//     * @return array
//     */
//    protected function getOptions()
//    {
//        return [
//            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
//        ];
//    }
}

<?php

namespace Modules\Laralite\Http\Controllers\Api;

use Log;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Laralite\Models\ImportedOrder;
use Modules\Laralite\Models\TempCsvData;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Models\Order;
use Modules\Laralite\Models\Product;
use Modules\Laralite\Services\OrderService;
use Symfony\Component\HttpFoundation\Response;
use Ramsey\Uuid\Uuid;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', '0');
class DataImportController extends Controller
{
    public $skuMap = [
       'TRPMUSEUMA' => 'TRAPMUSICTICKET',
       'SQ4865302' => 'SQ4865302',
    ];

    /**
     * @var OrderService
     */
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function upload(Request $request)
    {
        $file = $request->file('file');

        $allowedFileTypes = [
            'text/plain',
            'text/csv',
        ];

        if (!in_array($file->getMimeType(), $allowedFileTypes, true)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Filetype (' . $file->getMimeType() . ') not allowed, must be of type ' . implode(', ', $allowedFileTypes),
            ], Response::HTTP_BAD_REQUEST);
        }

        $csvArray = $this->csvToArray($file);
        $data = [];

        // Loop through CSV file and extract data into rows
        // This is a bit of a horrible beast I know....
        for ($i = 0; $i < count($csvArray); $i ++)
        {
            $data[] = [
                'order_id' => $csvArray[$i]['Order ID'] ?? ' ',
                'email' => $csvArray[$i]['Email'] ?? ' ',
                'financial_status' => $csvArray[$i]['Financial Status'] ?? ' ',
                'paid_at' => $csvArray[$i]['Paid at'] ?? ' ',
                'fulfillment_status' => $csvArray[$i]['Fulfillment Status'] ?? ' ',
                'fulfilled_at' => $csvArray[$i]['Fulfilled at'] ?? ' ',
                'currency' => $csvArray[$i]['Currency'] ?? ' ',
                'subtotal' => $csvArray[$i]['Subtotal'] ?? ' ',
                'shipping' => $csvArray[$i]['Shipping'] ?? ' ',
                'taxes' => $csvArray[$i]['Taxes'] ?? 0,
                'amount_refunded' => $csvArray[$i]['Amount Refunded'] ?? 0,
                'total' => $csvArray[$i]['Total'] ?? 0,
                'discount_code' => $csvArray[$i]['Discount Code'] ?? ' ',
                'discount_amount' => $csvArray[$i]['Discount Amount'] ?? 0,
                'shipping_method' => $csvArray[$i]['Shipping Method'] ?? ' ',
                'created_at' => $csvArray[$i]['Created at'] ?? ' ',
                'lineitem_quantity' => $csvArray[$i]['Lineitem quantity'] ?? 1,
                'lineitem_name' => $csvArray[$i]['Lineitem name'] ?? ' ',
                'lineitem_price' => $csvArray[$i]['Lineitem price'] ?? 0,
                'lineitem_sku' => $csvArray[$i]['Lineitem sku'] ?? ' ',
                'lineitem_variant' => $csvArray[$i]['Lineitem variant'] ?? ' ',
                'lineitem_requires_shipping' => $csvArray[$i]['Lineitem requires shipping'] ?? ' ',
                'lineitem_taxable' => $csvArray[$i]['Lineitem taxable'] ?? ' ',
                'lineitem_fulfillment_status' => $csvArray[$i]['Lineitem fulfillment status'] ?? ' ',
                'billing_name' => $csvArray[$i]['Billing Name'] ?? ' ',
                'billing_address_1' => $csvArray[$i]['Billing Address1'] ?? ' ',
                'billing_address_2' => $csvArray[$i]['Billing Address2'] ?? ' ',
                'billing_city' => $csvArray[$i]['Billing City'] ?? ' ',
                'billing_zip' => $csvArray[$i]['Billing Zip'] ?? ' ',
                'billing_province' => $csvArray[$i]['Billing Province'] ?? ' ',
                'billing_country' => $csvArray[$i]['Billing Country'] ?? ' ',
                'billing_phone' => $csvArray[$i]['Billing Phone'] ?? ' ',
                'shipping_name' => $csvArray[$i]['Shipping Name'] ?? ' ',
                'shipping_address_1' => $csvArray[$i]['Shipping Address1'] ?? ' ',
                'shipping_address_2' => $csvArray[$i]['Shipping Address2'] ?? ' ',
                'shipping_city' => $csvArray[$i]['Shipping City'] ?? ' ',
                'shipping_zip' => $csvArray[$i]['Shipping Zip'] ?? ' ',
                'shipping_province' => $csvArray[$i]['Shipping Province'] ?? ' ',
                'shipping_country' => $csvArray[$i]['Shipping Country'] ?? ' ',
                'shipping_phone' => $csvArray[$i]['Shipping Phone'] ?? ' ',
                'cancelled_at' => $csvArray[$i]['Cancelled at'] ?? ' ',
                'private_notes' => $csvArray[$i]['Private Notes'] ?? ' ',
                'channel_type' => $csvArray[$i]['Channel Type'] ?? ' ',
                'channel_name' => $csvArray[$i]['Channel Name'] ?? ' ',
                'channel_order_number' => $csvArray[$i]['Channel Order Number'] ?? ' ',
                'ga_tax' => $csvArray[$i]['GA Tax'] ?? ' ',
                'payment_method' => $csvArray[$i]['Payment Method'] ?? ' ',
                'payment_reference' => $csvArray[$i]['Payment Reference'] ?? ' ',
            ];
        }

        try {
            // Split into chunks as file is quite large
            foreach (array_chunk($data, 500) as $chunk)  
            {
                DB::table('temp_csv_data')->insert($chunk); 
            }
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Error when writing file to temp table',
            ]);
        }

        $tempRows = TempCsvData::all();

        return new JsonResponse([
            'success' => true,
            'message' => 'File Uploaded to temporary table successfully',
            'count' => count($tempRows),
        ]);
    }

    public function import()
    {
        $tempRows = TempCsvData::all();
        $orders = 0;
        $customers = 0;
        $skipped = [];

        if ($tempRows->isEmpty()) {
            return new JsonResponse([
                'success' => false,
                'message' => 'No rows in temporary table. Please import a CSV file.',
            ]);
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
            } catch (\Throwable $exception) {
                $customer = Customer::create([
                    'unique_id' => Uuid::uuid4(),
                    'name' => $tempRow->billing_name,
                    'email' => $tempRow->email,
                ]);

                $customers++;
            }


            try {
                // Find product relating to order
                if (!isset($this->skuMap[$tempRow->lineitem_sku])) {
                    throw new \Exception('Unknown sku');
                }
                $newSku = $this->skuMap[$tempRow->lineitem_sku];
                $fetchedProduct = Product::whereJsonContains('variants', ['sku' => $newSku])->firstOrFail();
            } catch (\Throwable $exception) {
                // No product found ...
                // Log and skip for now...
                $skipped[] = $tempRow;
                Log::info('Skipping CSV row import as no product found', [
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

            $total = $tempRow->total ?: $productPrice * $productQuantity;

            if (!empty($tempRow->discount_code)) {
                $basket['discounts'][0]['code'] = $tempRow->discount_code;
                $basket['discounts'][0]['name'] = $tempRow->discount_code;
            }
            $basket['discountAmount'] = !empty($tempRow->discount_amount) ? (int)($tempRow->discount_amount * 100) : 0;
            $basket['taxAmount'] = !empty($tempRow->taxes) ? (int)($tempRow->taxes * 100) : 0;
            $basket['total'] = !empty($total) ? (int)($tempRow->total * 100) : 0;

            // Build payment result object
            $result = [];
            $result['id'] = $tempRow->payment_reference ?: '';


            if ($tempRow->financial_status === 'PAID' && strtolower($tempRow->fulfillment_status) === 'fulfilled') {
                $result['paid'] = true;
                $orderStatus = 'complete';
            }

            if ($tempRow->financial_status === 'PAID'  && strtolower($tempRow->fulfillment_status) === 'pending') {
                $result['paid'] = true;
                $orderStatus = 'pending';
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
                    'confirmation_code' => $this->orderService->generateUniqueCode('TRAP-'),
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
                Log::error('Error occurred during order creation from import of order ID '
                    . $tempRow['order_id'] . ': ' .  $e->getMessage());
                continue;
            }

            $importedOrder = new ImportedOrder(['ext_order_id' => $tempRow['order_id'], 'order_id' => $insertedOrder->unique_id]);
            $importedOrder->save();
            $orders++;
        }

        // Clear the temporary table
        TempCsvData::truncate();

        return (new JsonResponse([
            'success' => true,
            'message' => 'Sucessfully imported data.',
            'data' => [
                'orders_created' => $orders,
                'customers_created' => $customers,
                'skipped_rows' => $skipped,
            ],
        ]))->setStatusCode(Response::HTTP_OK);
    }

    private function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }
    
        $header = null;
        $data = array();

        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }

            fclose($handle);
        }
    
        return $data;
    }
}

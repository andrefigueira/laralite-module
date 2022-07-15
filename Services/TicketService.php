<?php

namespace Modules\Laralite\Services;

use Barryvdh\DomPDF\Facade as PDF;
use Endroid\QrCode\QrCode;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Modules\Laralite\Exceptions\AppException;
use Modules\Laralite\Models\Order;
use Modules\Laralite\Models\Product;
use Modules\Laralite\Models\Ticket;
use Modules\Laralite\Models\TicketScans;
use Modules\Laralite\Services\Models\Basket\Item;
use Ramsey\Uuid\Uuid;

class TicketService
{
    /**
     * @var SettingsService
     */
    private SettingsService $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    /**
     * @param string|int|Ticket $idOrModel
     * @param array $relationships
     * @return Ticket|null
     * @throws AppException
     */
    public function redeemTicket($idOrModel, array $relationships = []): ?Ticket
    {
        /** @var Ticket $ticket */
        $ticket = $idOrModel instanceof Ticket ? $idOrModel : $this->getTicketByUuid($idOrModel, $relationships);

        if ($ticket) {

            if ($ticket->order->order_status !== 'complete' && $ticket->order->order_status !== 'pending') {
                throw new AppException('Cannot redeem ticket: order is incomplete');
            }

            if ($ticket->order->refunded === false) {
                throw new AppException('Cannot redeem ticket: order is has already been refunded');
            }

            $ticket->status = Ticket::STATUS_REDEEMED;
            $ticket->visited_counts = '1';
            $ticket->updateStatusLog('REDEEMED');
            $ticket->save();

            TicketScans::create([
                'order_id' => $ticket->order_id,
                'ticket_id' => $ticket->id,
                'customer_id' => $ticket->customer_id,
                'status' => $ticket->status,
            ]);
        }

        return $ticket;
    }

    /**
     * @param string|int|Ticket $idOrModel
     * @param array $relationships
     * @return Ticket|null
     * @throws AppException
     */
    public function unredeemTicket($idOrModel, array $relationships = []): ?Ticket
    {
        /** @var Ticket $ticket */
        $ticket = $idOrModel instanceof Ticket ? $idOrModel : $this->getTicketByUuid($idOrModel, $relationships);

        if ($ticket) {
            if ($ticket->status !== Ticket::STATUS_REDEEMED) {
                throw new AppException('Cannot unredeem ticket that has not been redeemed!');
            }

            $ticket->status = Ticket::STATUS_UNREDEEMED;
            $ticket->updateStatusLog('UNREDEEMED');
            $ticket->save();
        }

        return $ticket;
    }

    /**
     * @param string|int|Ticket $idOrModel
     * @param array $relationships
     * @return Ticket|null
     * @throws AppException
     */
    public function cancelTicket($idOrModel, array $relationships = []): ?Ticket
    {
        /** @var Ticket $ticket */
        $ticket = $idOrModel instanceof Ticket ? $idOrModel : $this->getTicketByUuid($idOrModel, $relationships);

        if ($ticket->id) {
            if ($ticket->status === Ticket::STATUS_CANCELLED) {
                throw new AppException('Ticket is already cancelled!');
            }

            if ($ticket->status === Ticket::STATUS_REDEEMED) {
                throw new AppException('Cannot cancel a ticket that is REDEEMED');
            }

            $ticket->status = Ticket::STATUS_CANCELLED;
            $ticket->updateStatusLog(Ticket::STATUS_CANCELLED);
            $ticket->save();
        }

        return $ticket;
    }

    /**
     * @param $id
     * @param array $relationships
     * @return Builder|Model|object|null
     */
    public function getTicketById($id, array $relationships = [])
    {
        return $this->getTicketBy($id, 'id', $relationships);
    }

    /**
     * @param $id
     * @param array $relationships
     * @return Ticket|object|null
     */
    public function getTicketByUuid($id, array $relationships = []): ?Ticket
    {
        return $this->getTicketBy($id, 'unique_id', $relationships);
    }

    /**
     * @param $id
     * @param string $idType
     * @param array $relationships
     * @return Ticket|object|null
     */
    private function getTicketBy($id, string $idType, array $relationships): ?Ticket
    {
        if ($relationships) {
            return Ticket::with($relationships)
                ->where($idType, '=', $id)->first();
        }

        return Ticket::where($idType, '=', $id)->first();
    }

    /**
     * @param $index
     * @param $product
     * @param $order
     * @param $customer
     * @return array
     */
    public function getGeneratedTickets($index, $product, $order, $customer)
    {
        $quantityGenerated = 0;
        $quantityToGenerate = $product['quantity'];
        $generatedTickets = [];

        // If product variant is `groupable` create just one ticket for the group
        if (isset($product['groupable']) && $product['groupable'] === true) {

            $ticketUuid = Uuid::uuid4();
            $generatedTicket = $this->generateTicket($ticketUuid);

            /** @var Ticket $generatedTickets */
            $generatedTickets = Ticket::create([
                'sku' => $product['sku'],
                'unique_id' => $ticketUuid,
                'customer_id' => $customer->id,
                'order_id' => $order->id,
                'ticket' => [
                    'image' => $generatedTicket->writeDataUri(),
                ],
                'admit_quantity' => $quantityToGenerate,
                'status_log' => Ticket::generateInitialStatusLogEntry(),
            ]);
            // else create each individual ticket
        } else {
            while ($quantityGenerated < $quantityToGenerate) {
                $ticketUuid = Uuid::uuid4();
                $generatedTicket = $this->generateTicket($ticketUuid);

                $generatedTickets[] = Ticket::create([
                    'sku' => $product['sku'],
                    'unique_id' => $ticketUuid,
                    'customer_id' => $customer->id,
                    'order_id' => $order->id,
                    'ticket' => [
                        'image' => $generatedTicket->writeDataUri(),
                    ],
                    'admit_quantity' => 1,
                    'status_log' => Ticket::generateInitialStatusLogEntry(),
                ]);

                $quantityGenerated++;
            }
        }

        return $generatedTickets;
    }

    /**
     * @param $ticketUuid
     * @return QrCode
     */
    private function generateTicket($ticketUuid): QrCode
    {
        $qrCode = new QrCode($ticketUuid);
        $qrCode->setSize(300);
        $qrCode->setMargin(10);

        return $qrCode;
    }

    /**
     * @param string $uuid
     * @param false $htmlView
     * @return Application|Factory|Response|View
     */
    public function generateTicketView(string $uuid, $htmlView = false)
    {
        try {
            /** @var Ticket $ticket */
            $ticket = Ticket::where('unique_id', '=', $uuid)->firstOrFail();
        } catch (\Throwable $exception) {
            \Log::critical('Failed to load ticket by uuid: ' . $uuid);
            abort(404);
        }

        $product = Product::whereJsonContains('variants', ['sku' => $ticket->sku])->firstOrFail();
        $productName = $product->name;
        $ticketUuid = $ticket->unique_id;
        $ticketQrCode = $ticket->ticket->image;
        $ticketAdmitQuantity = $ticket->admit_quantity ?? 1;
        $confirmation_code = $ticket->order->confirmation_code;

        $products = $ticket->order->basket->products;
        $ticketPrice = 0;
        $currency = $this->settingsService->getCurrency()['currency_symbol'] ?? '';

        foreach ($products as $product) {
            if ($ticket->sku === $product->sku) {
                $ticketPrice = ($product->price / 100);
                break;
            }
        }

        // For Testing
        // For some reason SSL was messing with my Image requests....
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ]);

        if (!$htmlView) {
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => false]);
            $pdf->getDomPDF()->setHttpContext($context);

            $pdf->loadView('trapmusicmuseum::ticket',
                compact(
                    'ticketUuid',
                    'ticketQrCode',
                    'ticketPrice',
                    'ticketAdmitQuantity',
                    'currency',
                    'confirmation_code',
                    'productName'
                )
            );

            return $pdf->stream();
        }

        return view('trapmusicmuseum::ticket', compact(
            'ticketUuid',
            'ticketQrCode',
            'ticketPrice',
            'ticketAdmitQuantity',
            'currency',
            'confirmation_code',
            'productName'
        ));
    }
}

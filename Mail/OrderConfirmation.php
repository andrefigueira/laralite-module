<?php

namespace Modules\Laralite\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Laralite\Models\Order;
use Modules\Laralite\Services\TicketService;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var array
     */
    public $form;

    /**
     * Create a new message instance.
     *
     * @param array $form
     */
    public function __construct(array $form)
    {
        $this->form = $form;
    }

    /**
     * Build the message.
     *
     * @param TicketService $ticketService
     * @return $this
     */
    public function build(TicketService $ticketService): OrderConfirmation
    {
        $this->from('noreply@trapmusicmuseum.us');
        $this->replyTo($this->form['customer']->email);
        $this->subject('Trap Music Museum - Order Confirmation');

        if (!empty($this->form['orderAssets'])) {
            foreach ($this->form['orderAssets'] as $ticket) {
                $this->attachData($ticketService->generateTicketView($ticket['unique_id']), 'ticket-' . $ticket['unique_id'] . '.pdf', [
                    'mime' => 'application/pdf',
                ]);
            }
        }

        $this->form['creditClaim'] = $this->form['order']->basket->creditClaim ?? false;

        return $this->view('laralite::mail.order-confirmation', [
            'form' => $this->form,
        ]);
    }
}

<?php

namespace Modules\Laralite\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

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
        $this->from('noreply@trapmusicmuseum.us');
        $this->replyTo($form['customer']->email);
        $this->subject('Trap Music Museum - Order Confirmation');

        if (!empty($form['orderAssets'])) {
            foreach ($form['orderAssets'] as $ticket) {
                 $this->attachData(generateTicketPDF($ticket['unique_id']), 'ticket-' . $ticket['unique_id'] . '.pdf', [
                     'mime' => 'application/pdf',
                 ]);
            }
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('laralite::mail.order-confirmation', [
            'form' => $this->form,
        ]);
    }
}

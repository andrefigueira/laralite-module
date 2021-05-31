<?php


namespace Modules\Laralite\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderRefundDetails extends Mailable
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
        $this->subject('Trap Music Museum - Order Refund Details');

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('laralite::mail.order-refund-details', [
            'form' => $this->form,
        ]);
    }
}

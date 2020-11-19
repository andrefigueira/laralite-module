<?php

namespace Modules\Laralite\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FormSubmitted extends Mailable
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
        $this->replyTo($form['email']);

        if ($this->form['formType'] === 'general') {
            $this->subject('Trap Music Museum - General Inquiry');
        }

        if ($this->form['formType'] === 'refunds') {
            $this->subject('Trap Music Museum - Refund Inquiry');
        }

        if ($this->form['formType'] === 'waiver') {
            $this->subject('Trap Music Museum - Waiver Signed');
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.email-received', [
            'form' => $this->form,
        ]);
    }
}

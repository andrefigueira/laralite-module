<?php

namespace Modules\Laralite\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build(): self
    {
        $this->from('noreply@trapmusicmuseum.us');
        $this->replyTo($this->data['customer']->email);
        $this->subject('Trap Music Museum - Subscription Confirmation');

        return $this->view('laralite::mail.subscription-confirmation', $this->data);
    }
}
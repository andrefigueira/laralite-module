<?php

namespace Modules\Laralite\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class ResetPasswordNotification extends ResetPassword
{
    public static $toMailCallback = [self::class, 'toEmailCallBack'];

    public static function toEmailCallBack($notifiable, $token): MailMessage
    {
        $queryString = [
            'token' => $token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ];

        $url = config('app.url') . '/reset-password?' . http_build_query($queryString);

        return (new MailMessage)
            ->subject('Reset Password Notification')
            ->view( 'laralite::mail.reset-password', ['url' => $url]);
        /*return (new MailMessage)
            ->subject(Lang::get('Reset Password Notification'))
            ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
            ->action(Lang::get('Reset Password'), $url)
            ->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->line(Lang::get('If you did not request a password reset, no further action is required.'));*/
    }
}

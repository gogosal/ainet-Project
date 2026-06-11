<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
    protected function buildMailMessage($url): MailMessage
    {
        return (new MailMessage)
            ->subject('FunShirt — Recuperação de password')
            ->view('emails.reset-password', [
                'url'  => $url,
                'name' => $this->notifiable->name,
            ]);
    }
}

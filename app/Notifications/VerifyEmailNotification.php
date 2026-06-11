<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends VerifyEmail
{
    protected function buildMailMessage($url): MailMessage
    {
        return (new MailMessage)
            ->subject('FunShirt — Verifica o teu email')
            ->view('emails.verify-email', [
                'url'  => $url,
                'name' => $this->notifiable->name,
            ]);
    }
}

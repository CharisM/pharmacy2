<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerificationCode extends Notification
{
    use Queueable;

    public function __construct(private readonly string $code) {}

    public function code(): string
    {
        return $this->code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Healthcare Pharmacy verification code')
            ->greeting('Hello '.$notifiable->name)
            ->line('Use this verification code to finish creating your account:')
            ->line($this->code)
            ->line('This code will expire in 10 minutes.');
    }
}

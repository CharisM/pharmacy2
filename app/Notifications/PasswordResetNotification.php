<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly string $url) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reset Your Healthcare Pharmacy Password')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('You requested a password reset. Click the button below to set a new password.')
            ->action('Reset Password', $this->url)
            ->line('This link will expire in 60 minutes.')
            ->line('If you did not request a password reset, no action is required.');
    }
}

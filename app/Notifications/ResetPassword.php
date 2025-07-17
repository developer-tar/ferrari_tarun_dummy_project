<?php

namespace App\Notifications;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification implements ShouldQueue {
    use Queueable;

    public $token;
    public $email;

    public function __construct($token, $email) {
        $this->token = $token;
        $this->email = $email;
    }

    public function via($notifiable) {
        return ['mail'];
    }

    public function toMail($notifiable) {
        $url = config('app.reset-password') . '?token=' . $this->token . '&email=' . urlencode($this->email);
        $expiry = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');

        return (new MailMessage)
            ->subject(__('emails.reset_password.subject'))
            ->greeting(__('emails.reset_password.greeting') . ', ' . ($notifiable->nickname ?? $notifiable->name))
            ->line(__('emails.reset_password.line1'))
            ->action(__('emails.reset_password.button'), $url)
            ->line(__('emails.reset_password.line2'))
            ->line(__('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->salutation(__('emails.reset_password.signature'))
            ->markdown('emails.tuners.reset', [
                'url' => $url,
                'name' => $notifiable->nickname ?? $notifiable->name,
                'expiryMinutes' => $expiry,
            ]);
    }
}

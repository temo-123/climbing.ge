<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as Notification;

class VerifyEmail extends Notification
{
    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        $hash = hash_hmac('sha256', $notifiable->email . '|' . $notifiable->id, config('app.key'));
        $base = rtrim(env('APP_SSH', 'https://') . env('USER_PAGE_URL', ''), '/');
        return $base . '/email/verify/' . $notifiable->id . '/' . $hash;
    }
}
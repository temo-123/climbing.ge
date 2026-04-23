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
        $hash = sha1($notifiable->email . $notifiable->created_at);
        $base = rtrim(env('MIX_APP_SSH', 'https://') . env('MIX_USER_PAGE_URL', ''), '/');
        return $base . '/email/verify/' . $notifiable->id . '/' . $hash;
    }
}
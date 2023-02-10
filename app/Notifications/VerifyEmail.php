<?php

namespace App\Notifications;

use Illuminate\Support\Facades\Crypt;

use Carbon\Carbon;
use Config;
use Illuminate\Auth\Notifications\VerifyEmail as Notification;
use Illuminate\Support\Facades\URL;

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
        $hash = Crypt::encrypt($notifiable->getKey());

        return env('FRONTPAGE_VERIFY_URL').$notifiable->id . '/' . $hash;

        // return config('frontend.email_verify_url') . $hash;

        // return URL::temporarySignedRoute(
        //     'api.verification.verify',
        //     Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
        //     [
        //         'id' => $notifiable->id,
        //         'hash' => Crypt::encrypt($notifiable->getKey()),
        //     ]
        // );
    }
}
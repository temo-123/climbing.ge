<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use App\Notifications\VerifyEmail as CustomVerifyEmail;

class MailConfirmationService
{
    /**
     * Send email verification to the user.
     *
     * @param User $user
     * @return bool
     */
    public function sendEmailVerification(User $user): bool
    {
        if ($user->hasVerifiedEmail()) {
            return false;
        }

        Notification::route('mail', $user->email)
            ->notify(new CustomVerifyEmail($user));

        return true;
    }

    /**
     * Resend email verification to the user.
     *
     * @param User $user
     * @return bool
     */
    public function resendEmailVerification(User $user): bool
    {
        if (! $user->hasVerifiedEmail()) {
            return $this->sendEmailVerification($user);
        }

        return false;
    }
}


<?php

namespace App\Observers;

use App\Models\User;
use App\Models\User\Following_users;

class UserObserver
{
    /**
     * A guest who subscribed via email and later registers a full account no
     * longer needs the old guest-follow row — they now have their own
     * user_notifications preferences, and keeping both would leave them
     * subscribed through two separate, unrelated systems for the same inbox.
     */
    public function created(User $user): void
    {
        Following_users::where('email', $user->email)->delete();
    }
}

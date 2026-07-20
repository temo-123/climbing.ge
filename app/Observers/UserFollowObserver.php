<?php

namespace App\Observers;

use App\Models\User\UserFollow;
use App\Services\FollowActivityNotificationService;

class UserFollowObserver
{
    public function created(UserFollow $follow): void
    {
        FollowActivityNotificationService::notifyOfNewFollower($follow);
    }
}

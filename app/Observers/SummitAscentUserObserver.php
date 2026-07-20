<?php

namespace App\Observers;

use App\Models\Summit\SummitAscentUser;
use App\Services\FollowActivityNotificationService;

class SummitAscentUserObserver
{
    public function created(SummitAscentUser $link): void
    {
        FollowActivityNotificationService::notifyFollowersOfAscent($link);
    }
}

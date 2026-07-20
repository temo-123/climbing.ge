<?php

namespace App\Observers;

use App\Models\Guide\Sport_route_review;
use App\Services\FollowActivityNotificationService;

class SportRouteReviewObserver
{
    public function created(Sport_route_review $review): void
    {
        FollowActivityNotificationService::notifyFollowersOfRouteReview($review);
    }
}

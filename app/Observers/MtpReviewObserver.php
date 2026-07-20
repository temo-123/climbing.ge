<?php

namespace App\Observers;

use App\Models\Guide\Mtp_review;
use App\Services\FollowActivityNotificationService;

class MtpReviewObserver
{
    public function created(Mtp_review $review): void
    {
        FollowActivityNotificationService::notifyFollowersOfMtpReview($review);
    }
}

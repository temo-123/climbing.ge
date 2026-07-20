<?php

namespace App\Observers;

use App\Models\Guide\Article_comment_user;
use App\Services\FollowActivityNotificationService;

class ArticleCommentUserObserver
{
    public function created(Article_comment_user $link): void
    {
        FollowActivityNotificationService::notifyFollowersOfComment($link);
    }
}

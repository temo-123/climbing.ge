<?php

namespace App\Services;

use App\Models\User;
use App\Models\Guide\Comment;
use App\Models\Guide\Article_comment_user;
use App\Models\Guide\Sector;
use App\Models\Summit\SummitAscent;
use App\Models\Summit\SummitAscentUser;
use App\Models\Guide\Sport_route_review;
use App\Models\Guide\Mtp_review;
use App\Models\User\UserFollow;
use App\Services\Notifications\Resolvers\ArticleResolver;
use App\Services\Notifications\Resolvers\SummitResolver;
use Illuminate\Database\Eloquent\Model;

/**
 * Emails a user's followers when that user does something publicly visible
 * on the guide/summit sites: comments on an article, logs a summit ascent,
 * or rates a route/MTP. Deliberately does NOT cover shop/purchase activity.
 */
class FollowActivityNotificationService
{
    public static function notifyFollowersOfComment(Article_comment_user $link): void
    {
        $actor = User::find($link->user_id);
        $comment = Comment::find($link->comment_id);
        if (!$actor || !$comment || !$comment->article) {
            return;
        }

        $resolver = new ArticleResolver();
        $article = $comment->article;

        self::dispatch($actor, $comment, $resolver->url($article), fn($locale) =>
            self::text('comment', $actor, $resolver->title($article, $locale), $locale)
        );
    }

    public static function notifyFollowersOfAscent(SummitAscentUser $link): void
    {
        $actor = User::find($link->user_id);
        $ascent = SummitAscent::find($link->ascent_id);
        if (!$actor || !$ascent || !$ascent->summit) {
            return;
        }

        $resolver = new SummitResolver();
        $summit = $ascent->summit;

        self::dispatch($actor, $ascent, $resolver->url($summit), fn($locale) =>
            self::text('ascent', $actor, $resolver->title($summit, $locale), $locale)
        );
    }

    public static function notifyFollowersOfRouteReview(Sport_route_review $review): void
    {
        $actor = $review->user_id ? User::find($review->user_id) : null;
        $route = $review->route;
        if (!$actor || !$route || !$route->sector_id) {
            return;
        }

        $article = Sector::find($route->sector_id)?->article;
        if (!$article) {
            return;
        }

        $resolver = new ArticleResolver();
        $routeName = $route->name;

        self::dispatch($actor, $review, $resolver->url($article), fn($locale) =>
            self::text('route_review', $actor, $routeName ?: $resolver->title($article, $locale), $locale)
        );
    }

    public static function notifyFollowersOfMtpReview(Mtp_review $review): void
    {
        $actor = $review->user_id ? User::find($review->user_id) : null;
        $mtp = $review->mtp;
        if (!$actor || !$mtp || !$mtp->sector_id) {
            return;
        }

        $article = Sector::find($mtp->sector_id)?->article;
        if (!$article) {
            return;
        }

        $resolver = new ArticleResolver();
        $mtpName = $mtp->name;

        self::dispatch($actor, $review, $resolver->url($article), fn($locale) =>
            self::text('mtp_review', $actor, $mtpName ?: $resolver->title($article, $locale), $locale)
        );
    }

    public static function notifyOfNewFollower(UserFollow $follow): void
    {
        $follower = User::find($follow->follower_id);
        $followed = User::find($follow->followed_id);
        if (!$follower || !$followed) {
            return;
        }

        $locale = $followed->lang === 'ka' ? 'ka' : 'us';
        $followerName = trim($follower->name . ' ' . $follower->surname);
        $url = config('app.base_url_ssh') . '/climber/' . $follower->id;

        $subject = $locale === 'ka' ? 'ახალი გამომწერი' : 'New follower';
        $text = $locale === 'ka'
            ? "ახალი გამომწერი: \"{$followerName}\" გამოგწერათ."
            : "New follower: \"{$followerName}\" followed you.";

        NotificationDispatchService::sendOnce($followed, $follow, 'new_follower', $url, $subject, $text);
    }

    private static function dispatch(User $actor, Model $notifiable, string $url, callable $textBuilder): void
    {
        $followers = $actor->followers()->get();
        if ($followers->isEmpty()) {
            return;
        }

        $actorName = trim($actor->name . ' ' . $actor->surname);

        foreach ($followers as $follower) {
            if ($follower->id === $actor->id) {
                continue;
            }

            $locale = $follower->lang === 'ka' ? 'ka' : 'us';
            $text = $textBuilder($locale);
            $subject = $locale === 'ka'
                ? $actorName . ' - ახალი აქტივობა'
                : $actorName . ' just posted new activity';

            NotificationDispatchService::sendOnce($follower, $notifiable, 'follow_activity', $url, $subject, $text);
        }
    }

    private static function text(string $kind, User $actor, string $title, string $locale): string
    {
        $actorName = trim($actor->name . ' ' . $actor->surname);

        $templates = [
            'comment' => [
                'us' => "{$actorName}, who you follow, just commented on \"{$title}\". Check it out.",
                'ka' => "{$actorName}, რომელსაც მიჰყვებით, კომენტარი დატოვა \"{$title}\"-ზე. გაეცანით.",
            ],
            'ascent' => [
                'us' => "{$actorName}, who you follow, just logged an ascent of \"{$title}\". Check it out.",
                'ka' => "{$actorName}, რომელსაც მიჰყვებით, ახალი აღსვლა დაფიქსირდა \"{$title}\"-ზე. გაეცანით.",
            ],
            'route_review' => [
                'us' => "{$actorName}, who you follow, just rated the route \"{$title}\". Check it out.",
                'ka' => "{$actorName}, რომელსაც მიჰყვებით, შეაფასა მარშრუტი \"{$title}\". გაეცანით.",
            ],
            'mtp_review' => [
                'us' => "{$actorName}, who you follow, just rated the MTP \"{$title}\". Check it out.",
                'ka' => "{$actorName}, რომელსაც მიჰყვებით, შეაფასა MTP \"{$title}\". გაეცანით.",
            ],
        ];

        return $templates[$kind][$locale] ?? $templates[$kind]['us'];
    }
}

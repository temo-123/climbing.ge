<?php

namespace App\Services;

use App\Models\User;
use App\Models\Guide\Donation;
use App\Models\Summit\SummitAscent;
use App\Models\Summit\SummitAscentUser;
use App\Models\Guide\Comment;
use App\Models\Guide\Article_comment_user;
use App\Models\User\Non_registered_commenter;

class GuestDataLinkingService
{
    /**
     * Link all guest data (donations, ascents, comments) to the newly
     * registered user by matching email, and clean up guest records.
     */
    public static function link_all(User $user): void
    {
        self::linkDonations($user);
        self::linkAscents($user);
        self::linkComments($user);
        self::cleanupGuestSubscriptions($user);
    }

    // Set user_id on all unlinked donations submitted with this email
    private static function linkDonations(User $user): void
    {
        Donation::where('email', $user->email)
            ->whereNull('user_id')
            ->update(['user_id' => $user->id]);
    }

    // Create summit_ascent_users records for ascents submitted with this email
    private static function linkAscents(User $user): void
    {
        $ascents = SummitAscent::where('email', $user->email)
            ->whereDoesntHave('users', fn($q) => $q->where('user_id', $user->id))
            ->get();

        foreach ($ascents as $ascent) {
            SummitAscentUser::firstOrCreate([
                'ascent_id' => $ascent->id,
                'user_id'   => $user->id,
            ]);
        }
    }

    // Link article comments to the user and publish them
    private static function linkComments(User $user): void
    {
        $comments = Comment::where('email', $user->email)
            ->whereDoesntHave('users', fn($q) => $q->where('user_id', $user->id))
            ->get();

        foreach ($comments as $comment) {
            Article_comment_user::firstOrCreate([
                'comment_id' => $comment->id,
                'user_id'    => $user->id,
            ]);
            if (!$comment->published) {
                $comment->update(['published' => 1]);
            }
        }
    }

    // Remove the guest email record — the user now has a full account with
    // user_notification preferences set up at registration.
    // Add more subscription table cleanups here as the site grows.
    private static function cleanupGuestSubscriptions(User $user): void
    {
        Non_registered_commenter::where('email', $user->email)->delete();
    }
}

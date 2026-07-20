<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\User\UserFollow;
use Illuminate\Http\Request;

class UserFollowController extends Controller
{
    public function follow(Request $request, $user_id)
    {
        $user = $request->user();

        if ((int) $user_id === $user->id) {
            return response()->json(['message' => "You can't follow yourself"], 422);
        }

        if (!User::where('id', $user_id)->exists()) {
            return response()->json(['message' => 'User not found'], 404);
        }

        UserFollow::firstOrCreate([
            'follower_id' => $user->id,
            'followed_id' => $user_id,
        ]);

        return response()->json(['message' => 'Followed', 'following' => true]);
    }

    public function unfollow(Request $request, $user_id)
    {
        $user = $request->user();

        UserFollow::where('follower_id', $user->id)
            ->where('followed_id', $user_id)
            ->delete();

        return response()->json(['message' => 'Unfollowed', 'following' => false]);
    }

    public function follow_status(Request $request, $user_id)
    {
        $user = $request->user();

        return response()->json([
            'following' => $user->isFollowing($user_id),
            'is_self' => (int) $user_id === $user->id,
        ]);
    }
}

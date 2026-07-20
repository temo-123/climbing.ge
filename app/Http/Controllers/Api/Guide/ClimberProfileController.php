<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Guide\Comment;
use App\Models\Guide\Article_comment_user;
use App\Models\Summit\SummitAscent;
use App\Models\Summit\SummitAscentUser;
use App\Models\Guide\Sport_route_review;
use App\Models\Guide\Mtp_review;
use App\Models\User\User_site;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClimberProfileController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        $authUser = auth('sanctum')->user();

        $users = User::select(['id', 'name', 'surname', 'image'])
            ->whereDoesntHave('role', fn($q) => $q->where('slug', 'ban'))
            ->whereNotNull('name')
            ->when($authUser, fn($q) => $q->where('id', '!=', $authUser->id))
            ->withCount('followers')
            ->orderBy('name')
            ->paginate(24);

        return response()->json($users);
    }

    public function show($user_id): JsonResponse
    {
        $user = User::select(['id', 'name', 'surname', 'image', 'my_bio', 'social_links', 'created_at'])->find($user_id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $commentIds = Article_comment_user::where('user_id', $user_id)->pluck('comment_id');
        $recentComments = Comment::whereIn('id', $commentIds)
            ->where('published', 1)
            ->where('admin_hidden', 0)
            ->with('article')
            ->latest('created_at')
            ->take(5)
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'text' => $c->text,
                'created_at' => $c->created_at,
                'article_url_title' => $c->article->url_title ?? null,
                'article_category' => $c->article->category ?? null,
            ]);

        $ascentIds = SummitAscentUser::where('user_id', $user_id)->pluck('ascent_id');
        $recentAscents = SummitAscent::whereIn('id', $ascentIds)
            ->with('summit')
            ->latest('ascent_date')
            ->take(5)
            ->get()
            ->map(fn($a) => [
                'id' => $a->id,
                'ascent_date' => $a->ascent_date,
                'summit_title' => $a->summit->title ?? null,
                'summit_url_title' => $a->summit->url_title ?? null,
            ]);

        $recentRouteReviews = Sport_route_review::where('user_id', $user_id)
            ->where('published', 1)
            ->where('admin_hidden', 0)
            ->with('route')
            ->latest('created_at')
            ->take(5)
            ->get()
            ->map(fn($r) => [
                'id' => $r->id,
                'stars' => $r->stars,
                'text' => $r->text,
                'created_at' => $r->created_at,
                'route_name' => $r->route->name ?? null,
            ]);

        $recentMtpReviews = Mtp_review::where('user_id', $user_id)
            ->where('published', 1)
            ->where('admin_hidden', 0)
            ->with('mtp')
            ->latest('created_at')
            ->take(5)
            ->get()
            ->map(fn($r) => [
                'id' => $r->id,
                'stars' => $r->stars,
                'text' => $r->text,
                'created_at' => $r->created_at,
                'mtp_name' => $r->mtp->name ?? null,
            ]);

        $followers = $user->followers()->select(['users.id', 'users.name', 'users.surname', 'users.image'])->take(30)->get();
        $following = $user->following()->select(['users.id', 'users.name', 'users.surname', 'users.image'])->take(30)->get();

        $userSites = User_site::where('user_id', $user_id)->get(['id', 'url']);

        return response()->json([
            'user' => $user,
            'followers_count' => $user->followers()->count(),
            'following_count' => $user->following()->count(),
            'followers' => $followers,
            'following' => $following,
            'user_sites' => $userSites,
            'recent_comments' => $recentComments,
            'recent_ascents' => $recentAscents,
            'recent_route_reviews' => $recentRouteReviews,
            'recent_mtp_reviews' => $recentMtpReviews,
        ]);
    }
}

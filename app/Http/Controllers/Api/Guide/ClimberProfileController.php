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
use App\Models\Guide\RouteAscent;
use App\Models\Guide\MtpAscent;
use App\Models\User\User_site;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClimberProfileController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        $authUser = auth('sanctum')->user();
        $search = trim((string) $request->query('search', ''));
        $sort = $request->query('sort', 'name') === 'top_active' ? 'top_active' : 'name';

        $query = User::select(['id', 'name', 'surname', 'image'])
            ->whereDoesntHave('role', fn($q) => $q->where('slug', 'ban'))
            ->whereNotNull('name')
            ->whereNotNull('email_verified_at')
            ->when($authUser, fn($q) => $q->where('id', '!=', $authUser->id))
            ->when($search !== '', fn($q) => $q->where(fn($sub) => $sub
                ->where('name', 'like', "%{$search}%")
                ->orWhere('surname', 'like', "%{$search}%")
            ))
            ->withCount('followers')
            ->withPointsCounts()
            // Only active climbers — anyone with at least one point-earning
            // contribution (visible review/comment, summit/route/MTP ascent).
            ->havingRaw(User::pointsOrderByExpression() . ' > 0');

        $query = $sort === 'top_active'
            ? $query->orderByRaw(User::pointsOrderByExpression() . ' desc')
            : $query->orderBy('name');

        $users = $query->paginate(24)
            ->through(function ($user) {
                $user->points_total = User::pointsFromCounts($user);
                return $user;
            });

        return response()->json($users);
    }

    public function show($user_id): JsonResponse
    {
        $user = User::select(['id', 'name', 'surname', 'image', 'my_bio', 'created_at'])
            ->whereDoesntHave('role', fn($q) => $q->where('slug', 'ban'))
            ->find($user_id);

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

        $recentRouteAscents = RouteAscent::where('user_id', $user_id)
            ->with('route:id,name,grade')
            ->latest('ascent_date')
            ->latest('id')
            ->take(5)
            ->get()
            ->map(fn($a) => [
                'id' => $a->id,
                'ascent_date' => $a->ascent_date?->format('Y-m-d'),
                'ascent_style' => $a->ascent_style,
                'route_name' => $a->route->name ?? null,
                'route_grade' => $a->route->grade ?? null,
            ]);

        $recentMtpAscents = MtpAscent::where('user_id', $user_id)
            ->with('mtp:id,name')
            ->latest('ascent_date')
            ->latest('id')
            ->take(5)
            ->get()
            ->map(fn($a) => [
                'id' => $a->id,
                'ascent_date' => $a->ascent_date?->format('Y-m-d'),
                'ascent_style' => $a->ascent_style,
                'mtp_name' => $a->mtp->name ?? null,
            ]);

        $counts = User::whereKey($user_id)->withPointsCounts()->first();

        $followers = $user->followers()->select(['users.id', 'users.name', 'users.surname', 'users.image'])->take(30)->get();
        $following = $user->following()->select(['users.id', 'users.name', 'users.surname', 'users.image'])->take(30)->get();

        $userSites = User_site::where('user_id', $user_id)->get(['id', 'url']);

        return response()->json([
            'user' => $user,
            'points_total' => User::pointsFromCounts($counts),
            'followers_count' => $user->followers()->count(),
            'following_count' => $user->following()->count(),
            'followers' => $followers,
            'following' => $following,
            'user_sites' => $userSites,
            'recent_comments' => $recentComments,
            'recent_ascents' => $recentAscents,
            'recent_route_reviews' => $recentRouteReviews,
            'recent_mtp_reviews' => $recentMtpReviews,
            'recent_route_ascents' => $recentRouteAscents,
            'recent_mtp_ascents' => $recentMtpAscents,
            // Same visibility-filtered counts the points are computed from.
            'comments_count' => (int) $counts->comment_count,
            'ascents_count' => (int) $counts->ascent_count,
            'route_ascents_count' => (int) $counts->route_ascent_count,
            'mtp_ascents_count' => (int) $counts->mtp_ascent_count,
            'route_reviews_count' => (int) $counts->route_review_count,
            'mtp_reviews_count' => (int) $counts->mtp_review_count,
        ]);
    }
}

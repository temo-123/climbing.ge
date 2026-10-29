<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guide\Article;
use App\Models\Guide\PartnerPost;
use App\Services\PermissionService;

class PartnerPostController extends Controller
{
    public function create_partner_post(Request $request)
    {
        $request->validate([
            'climb_date' => 'required|date|after_or_equal:today|before:' . now()->addMonths(6)->toDateString(),
            'end_date'   => 'nullable|date|after_or_equal:climb_date',
            'languages'  => 'nullable|array|max:6',
            'languages.*'=> 'string|in:' . implode(',', PartnerPost::LANGUAGES),
            'discipline' => 'required|in:' . implode(',', PartnerPost::DISCIPLINES),
            'article_id' => 'nullable|integer',
            'place'      => 'nullable|string|max:120',
            'grade_from' => 'nullable|string|max:8',
            'grade_to'   => 'nullable|string|max:8',
            'note'       => 'nullable|string|max:500',
            'contact'    => 'nullable|string|max:120',
        ]);

        if (!$request->article_id && !trim((string) $request->place)) {
            return response()->json(['message' => 'Choose an area or write a place.'], 422);
        }

        $end_date = $request->end_date && $request->end_date !== $request->climb_date ? $request->end_date : null;
        if ($end_date && \Carbon\Carbon::parse($request->climb_date)->diffInDays(\Carbon\Carbon::parse($end_date)) >= PartnerPost::MAX_TRIP_DAYS) {
            return response()->json(['message' => 'A trip can be at most ' . PartnerPost::MAX_TRIP_DAYS . ' days long.'], 422);
        }

        $open = PartnerPost::where('user_id', $request->user()->id)
            ->notOver()
            ->count();
        if ($open >= PartnerPost::MAX_OPEN_PER_USER) {
            return response()->json(['message' => 'You already have ' . PartnerPost::MAX_OPEN_PER_USER . ' open posts.'], 429);
        }

        $article_id = $request->article_id
            ? Article::whereIn('category', ['outdoor', 'ice', 'indoor', 'mount_route'])->where('id', $request->article_id)->value('id')
            : null;

        $post = PartnerPost::create([
            'user_id'    => $request->user()->id,
            'article_id' => $article_id,
            'place'      => $request->place ? strip_tags($request->place) : null,
            'climb_date' => $request->climb_date,
            'end_date'   => $end_date,
            'discipline' => $request->discipline,
            'grade_from' => $request->grade_from ? strip_tags($request->grade_from) : null,
            'grade_to'   => $request->grade_to ? strip_tags($request->grade_to) : null,
            'languages'  => $request->languages ? array_values(array_unique($request->languages)) : null,
            'note'       => $request->note ? strip_tags($request->note) : null,
            'contact'    => $request->contact ? strip_tags($request->contact) : null,
        ]);

        return response()->json(['message' => 'Post published', 'post_id' => $post->id], 201);
    }

    public function get_my_partner_posts(Request $request)
    {
        return PartnerPost::where('user_id', $request->user()->id)->latest('id')->limit(50)->pluck('id');
    }

    public function del_my_partner_post(Request $request, $post_id)
    {
        $post = PartnerPost::where('id', $post_id)->where('user_id', $request->user()->id)->first();
        if (!$post) return response()->json(['error' => 'Not found'], 404);

        $post->delete();

        return response()->json(['success' => true]);
    }

    // guide dashboard → Community tab: upcoming and recent posts incl. hidden ones
    public function get_partner_posts_admin()
    {
        $auth = PermissionService::authorize('comment', 'show');
        if ($auth) return $auth;

        return PartnerPost::with(['user:id,name,surname,email', 'article:id,category,url_title'])
            ->whereDate('climb_date', '>=', now()->subDays(30)->toDateString())
            ->orderByDesc('climb_date')
            ->limit(200)
            ->get();
    }

    // moderation shares the comment permissions, like condition reports
    public function hide_partner_post_admin($post_id)
    {
        $auth = PermissionService::authorize('comment', 'del');
        if ($auth) return $auth;

        $post = PartnerPost::findOrFail($post_id);
        $post->admin_hidden = !$post->admin_hidden;
        $post->save();

        return response()->json(['success' => true, 'admin_hidden' => $post->admin_hidden]);
    }
}

<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use App\Models\Guide\PartnerPost;

class PartnerPostController extends Controller
{
    /**
     * Upcoming partner posts — climber restricted to public-safe columns,
     * area as its url_title + category so the page can link to it.
     */
    public function get_partner_posts()
    {
        // notes and contacts are for logged-in climbers only (dual-flow route → sanctum guard)
        $guest = !auth('sanctum')->check();

        $posts = PartnerPost::where('admin_hidden', false)
            ->whereDate('climb_date', '>=', now()->toDateString())
            ->with(['user:id,name,surname,image', 'article:id,category,url_title'])
            ->orderBy('climb_date')
            ->orderByDesc('id')
            ->limit(100)
            ->get(['id', 'user_id', 'article_id', 'place', 'climb_date', 'discipline', 'grade_from', 'grade_to', 'note', 'contact', 'created_at']);

        if ($guest) {
            $posts->each(function ($post) {
                $post->has_note = !empty($post->note);
                $post->has_contact = !empty($post->contact);
                $post->note = null;
                $post->contact = null;
            });
        }

        return $posts;
    }
}

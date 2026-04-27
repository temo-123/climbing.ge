<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class InstagramController extends Controller
{
    public function get_posts(Request $request, string $tag = '')
    {
        $token = env('INSTAGRAM_ACCESS_TOKEN');

        if (!$token) {
            return response()->json(['posts' => [], 'configured' => false]);
        }

        $cacheKey = 'instagram_posts_' . ($tag ?: 'all');

        $posts = Cache::remember($cacheKey, 900, function () use ($token, $tag) {
            $response = Http::timeout(8)->get('https://graph.instagram.com/me/media', [
                'fields'       => 'id,caption,media_type,media_url,thumbnail_url,permalink,timestamp',
                'access_token' => $token,
                'limit'        => 50,
            ]);

            if (!$response->successful()) {
                return [];
            }

            $all = $response->json('data', []);

            if ($tag) {
                $all = array_filter($all, function ($post) use ($tag) {
                    $caption = strtolower($post['caption'] ?? '');
                    return str_contains($caption, '#' . strtolower($tag));
                });
            }

            return array_slice(array_values($all), 0, 12);
        });

        return response()->json(['posts' => $posts, 'configured' => true]);
    }
}

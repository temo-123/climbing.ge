<?php

namespace App\Http\Controllers\Api\Summit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Summit\Summit;
use App\Models\Summit\SummitAscent;
use App\Models\Summit\SummitAscentUser;
use App\Models\Summit\SummitAscentRoute;
use App\Models\Guide\Route;
use App\Models\User;
use App\Services\ReCaptchaV3Service;
use App\Services\Abstract\ImageControllService;

class SummitPublicController extends Controller
{
    // Maps articles.category to the guide subdomain's URL path segment (see resources/js/routes/SiteRoutes.js)
    private const ARTICLE_CATEGORY_PATHS = [
        'outdoor'       => 'outdoor',
        'indoor'        => 'indoor',
        'ice'           => 'ice',
        'other'         => 'other',
        'mount_route'   => 'mountaineering',
        'news'          => 'news',
        'tech_tip'      => 'tech_tip',
        'partners'      => 'partner',
        'special'       => 'special_article',
        'spot_projects' => 'spot_project',
    ];

    public function index()
    {
        $summits = Summit::where('published', 1)
            ->orderBy('title')
            ->get()
            ->map(function ($summit) {
                return [
                    'id'        => $summit->id,
                    'title'     => $summit->title,
                    'url_title' => $summit->url_title,
                    'height'    => $summit->height,
                    'latitude'  => $summit->latitude,
                    'longitude' => $summit->longitude,
                    'image'     => $summit->image,
                ];
            });

        return response()->json($summits);
    }

    public function show($url_title)
    {
        $summit = Summit::where('url_title', $url_title)
            ->whereIn('published', [1, 2])
            ->firstOrFail();

        return response()->json([
            'id'          => $summit->id,
            'title'       => $summit->title,
            'url_title'   => $summit->url_title,
            'description' => $summit->description,
            'image'       => $summit->image,
            'height'      => $summit->height,
            'latitude'    => $summit->latitude,
            'longitude'   => $summit->longitude,
            'qr_code'     => $summit->qr_code,
        ]);
    }

    public function get_routes_for_summit($id)
    {
        $summit = Summit::with([
            'mountRoutes.article.global_article_us',
            'mountRoutes.article.global_article_ka',
        ])->findOrFail($id);

        $routes = $summit->mountRoutes->map(function ($smr) {
            $a = $smr->article;
            if (!$a) return null;
            return [
                'id'        => $a->id,
                'name'      => $a->global_article_us?->title ?? $a->global_article_ka?->title ?? $a->url_title,
                'grade'     => $a->mount_grade ?: null,
                'url_title' => $a->url_title,
            ];
        })->filter()->values();

        return response()->json($routes);
    }

    public function submit_ascent(Request $request, $summit_id)
    {
        $captcha = new ReCaptchaV3Service();
        if ($captcha->isConfigured()) {
            $token = $request->input('recaptcha_token');
            if (!$token || !$captcha->verify($token, $request->ip(), 0.5)) {
                return response()->json(['message' => 'reCAPTCHA verification failed. Please try again.'], 422);
            }
        }

        $summit = Summit::findOrFail($summit_id);

        $authUser = auth('sanctum')->user();

        $request->validate([
            'name'          => $authUser ? 'nullable|string|max:100' : 'required|string|max:100',
            'surname'       => $authUser ? 'nullable|string|max:100' : 'required|string|max:100',
            'email'         => 'nullable|email|max:255',
            'article_id'    => 'nullable|integer|exists:articles,id',
            'other_route'   => 'nullable|string|max:255',
            'comment'       => 'nullable|string',
            'photo'         => 'nullable|image|max:10240',
            'user_latitude' => 'nullable|numeric',
            'user_longitude'=> 'nullable|numeric',
        ]);

        // GPS validation via Haversine
        $isGpsValidated = false;
        if (
            $request->user_latitude !== null &&
            $request->user_longitude !== null &&
            $summit->latitude !== null &&
            $summit->longitude !== null
        ) {
            $distance = $this->haversine(
                (float) $request->user_latitude,
                (float) $request->user_longitude,
                (float) $summit->latitude,
                (float) $summit->longitude
            );
            $isGpsValidated = $distance <= 20;
        }

        // Handle photo upload
        $photoPath = ImageControllService::image_upload('images/sommit_ascents_img/', $request, 'photo', 2);

        // Create ascent record
        $ascent = SummitAscent::create([
            'summit_id'       => $summit->id,
            'name'            => $authUser ? $authUser->name    : $request->name,
            'surname'         => $authUser ? $authUser->surname : $request->surname,
            'email'           => $authUser ? $authUser->email   : $request->email,
            'route_id'        => $request->route_id,
            'other_route'     => $request->other_route,
            'comment'         => $request->comment,
            'photo'           => $photoPath,
            'is_gps_validated'=> $isGpsValidated,
            'user_latitude'   => $request->user_latitude,
            'user_longitude'  => $request->user_longitude,
            'ascent_date'     => now()->toDateString(),
            'ascent_time'     => now()->format('H:i'),
        ]);

        // Create ascent route record
        if ($request->article_id || $request->other_route) {
            SummitAscentRoute::create([
                'ascent_id'        => $ascent->id,
                'article_id'       => $request->article_id,
                'other_route_name' => $request->other_route,
            ]);
        }

        // User matching
        $matchedUserIds = collect();

        if ($authUser) {
            // Logged-in: link directly by auth user id
            $matchedUserIds->push($authUser->id);
        } elseif ($request->email) {
            // Guest with email: check users table, link only if found
            $matched = User::where('email', $request->email)->pluck('id');
            $matchedUserIds = $matchedUserIds->merge($matched);
        }
        // Guest with no email: no relation created, ascent saved anonymously

        $matchedUserIds = $matchedUserIds->unique();

        foreach ($matchedUserIds as $userId) {
            SummitAscentUser::create([
                'ascent_id' => $ascent->id,
                'user_id'   => $userId,
            ]);
        }

        return response()->json([
            'message'             => 'Ascent recorded successfully',
            'ascent_id'           => $ascent->id,
            'is_gps_validated'    => $isGpsValidated,
            'matched_users_count' => $matchedUserIds->count(),
        ], 201);
    }

    public function get_ascents($url_title)
    {
        $summit = Summit::where('url_title', $url_title)->whereIn('published', [1, 2])->firstOrFail();

        $ascents = $summit->ascents()
            ->with([
                'ascentRoutes.article.global_article_us',
                'ascentRoutes.article.global_article_ka',
            ])
            ->orderBy('ascent_date', 'desc')
            ->get()
            ->map(function ($ascent) {
                $routeName  = null;
                $routeGrade = null;
                $routeArticleUrl = null;
                $routeArticlePath = null;

                if ($ascent->ascentRoutes->isNotEmpty()) {
                    $ar = $ascent->ascentRoutes->first();
                    if ($ar->article) {
                        $routeName  = $ar->article->global_article_us?->title
                            ?? $ar->article->global_article_ka?->title
                            ?? $ar->article->url_title;
                        $routeGrade = $ar->article->mount_grade ?: null;

                        // Only link to the article if it's actually public (published 0 = not public).
                        if (in_array($ar->article->published, [1, 2])) {
                            $routeArticleUrl  = $ar->article->url_title;
                            $routeArticlePath = self::ARTICLE_CATEGORY_PATHS[$ar->article->category] ?? null;
                        }
                    } elseif ($ar->other_route_name) {
                        $routeName = $ar->other_route_name;
                    }
                }

                return [
                    'id'                => $ascent->id,
                    'name'              => $ascent->name,
                    'surname'           => $ascent->surname,
                    'comment'           => $ascent->comment,
                    'photo'             => $ascent->photo,
                    'is_gps_validated'  => $ascent->is_gps_validated,
                    'ascent_date'       => $ascent->ascent_date,
                    'ascent_time'       => $ascent->ascent_time,
                    'route_name'        => $routeName,
                    'route_grade'       => $routeGrade,
                    'route_article_url' => ($routeArticleUrl && $routeArticlePath) ? $routeArticlePath . '/' . $routeArticleUrl : null,
                ];
            });

        return response()->json(['summit' => ['id' => $summit->id, 'title' => $summit->title], 'ascents' => $ascents]);
    }

    public function my_ascents()
    {
        $userId = auth('sanctum')->id();

        $ascents = SummitAscentUser::where('user_id', $userId)
            ->with(['ascent.summit', 'ascent.ascentRoutes.route'])
            ->get()
            ->map(function ($au) {
                $ascent = $au->ascent;
                if (!$ascent) return null;

                $routeName  = null;
                $routeGrade = null;

                if ($ascent->ascentRoutes->isNotEmpty()) {
                    $ar = $ascent->ascentRoutes->first();
                    if ($ar->route) {
                        $routeName  = $ar->route->name;
                        $routeGrade = $ar->route->grade;
                    } elseif ($ar->other_route_name) {
                        $routeName = $ar->other_route_name;
                    }
                }

                return [
                    'id'               => $ascent->id,
                    'summit_id'        => $ascent->summit_id,
                    'summit_title'     => $ascent->summit?->title,
                    'summit_url'       => $ascent->summit?->url_title,
                    'name'             => $ascent->name,
                    'surname'          => $ascent->surname,
                    'comment'          => $ascent->comment,
                    'photo'            => $ascent->photo,
                    'is_gps_validated' => $ascent->is_gps_validated,
                    'ascent_date'      => $ascent->ascent_date,
                    'ascent_time'      => $ascent->ascent_time,
                    'route_name'       => $routeName,
                    'route_grade'      => $routeGrade,
                ];
            })
            ->filter()
            ->sortByDesc('ascent_date')
            ->values();

        return response()->json($ascents);
    }

    private function haversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $R    = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a    = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;
        return $R * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}

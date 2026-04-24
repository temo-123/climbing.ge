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

class SummitPublicController extends Controller
{
    public function index()
    {
        $summits = Summit::with('region')
            ->where('published', true)
            ->orderBy('title')
            ->get()
            ->map(function ($summit) {
                return [
                    'id'          => $summit->id,
                    'title'       => $summit->title,
                    'url_title'   => $summit->url_title,
                    'height'      => $summit->height,
                    'latitude'    => $summit->latitude,
                    'longitude'   => $summit->longitude,
                    'image'       => $summit->image,
                    'region'      => $summit->region ? [
                        'id'      => $summit->region->id,
                        'us_name' => $summit->region->us_name,
                        'ka_name' => $summit->region->ka_name,
                    ] : null,
                ];
            });

        return response()->json($summits);
    }

    public function show($url_title)
    {
        $summit = Summit::with('region')
            ->where('url_title', $url_title)
            ->where('published', true)
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
            'region'      => $summit->region ? [
                'id'      => $summit->region->id,
                'us_name' => $summit->region->us_name,
                'ka_name' => $summit->region->ka_name,
            ] : null,
        ]);
    }

    public function get_routes_for_summit($id)
    {
        $summit = Summit::findOrFail($id);

        $routes = Route::orderBy('name')
            ->limit(50)
            ->get(['id', 'name', 'grade']);

        return response()->json($routes);
    }

    public function submit_ascent(Request $request, $summit_id)
    {
        $summit = Summit::findOrFail($summit_id);

        $request->validate([
            'name'          => 'required|string|max:100',
            'surname'       => 'required|string|max:100',
            'email'         => 'nullable|email|max:255',
            'route_id'      => 'nullable|integer|exists:routes,id',
            'other_route'   => 'nullable|string|max:255',
            'comment'       => 'nullable|string',
            'photo'         => 'nullable|image|max:10240',
            'user_latitude' => 'nullable|numeric',
            'user_longitude'=> 'nullable|numeric',
            'ascent_date'   => 'nullable|date',
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
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('ascents', 'public');
        }

        // Create ascent record
        $ascent = SummitAscent::create([
            'summit_id'       => $summit->id,
            'name'            => $request->name,
            'surname'         => $request->surname,
            'email'           => $request->email,
            'route_id'        => $request->route_id,
            'other_route'     => $request->other_route,
            'comment'         => $request->comment,
            'photo'           => $photoPath,
            'is_gps_validated'=> $isGpsValidated,
            'user_latitude'   => $request->user_latitude,
            'user_longitude'  => $request->user_longitude,
            'ascent_date'     => $request->ascent_date ?? now()->toDateString(),
        ]);

        // Create ascent route record
        if ($request->route_id || $request->other_route) {
            SummitAscentRoute::create([
                'ascent_id'        => $ascent->id,
                'route_id'         => $request->route_id,
                'other_route_name' => $request->other_route,
            ]);
        }

        // User matching
        $matchedUserIds = collect();

        if (auth('sanctum')->check()) {
            // Auth user: link directly
            $matchedUserIds->push(auth('sanctum')->id());
        } elseif ($request->email) {
            $byEmail = User::where('email', $request->email)->pluck('id');
            $matchedUserIds = $matchedUserIds->merge($byEmail);
        } else {
            $byName = User::where('name', $request->name)
                ->where('surname', $request->surname)
                ->pluck('id');
            $matchedUserIds = $matchedUserIds->merge($byName);
        }

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
        $summit = Summit::where('url_title', $url_title)->where('published', true)->firstOrFail();

        $ascents = $summit->ascents()
            ->with(['ascentRoutes.route.sector'])
            ->orderBy('ascent_date', 'desc')
            ->get()
            ->map(function ($ascent) {
                $routeName  = null;
                $routeGrade = null;
                $routeArticleUrl = null;

                if ($ascent->ascentRoutes->isNotEmpty()) {
                    $ar = $ascent->ascentRoutes->first();
                    if ($ar->route) {
                        $routeName  = $ar->route->name;
                        $routeGrade = $ar->route->grade;
                        if ($ar->route->sector && $ar->route->sector->article) {
                            $routeArticleUrl = $ar->route->sector->article->url_title;
                        }
                    } elseif ($ar->other_route_name) {
                        $routeName = $ar->other_route_name;
                    }
                }

                return [
                    'id'               => $ascent->id,
                    'name'             => $ascent->name,
                    'surname'          => $ascent->surname,
                    'comment'          => $ascent->comment,
                    'photo'            => $ascent->photo,
                    'is_gps_validated' => $ascent->is_gps_validated,
                    'ascent_date'      => $ascent->ascent_date,
                    'route_name'       => $routeName,
                    'route_grade'      => $routeGrade,
                    'route_article_url'=> $routeArticleUrl,
                ];
            });

        return response()->json(['summit' => ['id' => $summit->id, 'title' => $summit->title], 'ascents' => $ascents]);
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

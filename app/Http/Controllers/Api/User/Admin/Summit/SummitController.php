<?php

namespace App\Http\Controllers\Api\User\Admin\Summit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Summit\Summit;
use App\Models\Summit\SummitAscent;
use App\Models\Guide\Region;
use App\Models\Guide\Article;
use App\Models\Guide\Locale_article;

class SummitController extends Controller
{
    public function index()
    {
        $summits = Summit::with(['region', 'mountRoute.global_article_us', 'mountRoute.global_article_ka'])
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($summit) {
                $mr = $summit->mountRoute;
                $mr_name = $mr?->global_article_us?->title ?? $mr?->global_article_ka?->title ?? null;
                return [
                    'id'               => $summit->id,
                    'title'            => $summit->title,
                    'ka_title'         => $summit->ka_title,
                    'url_title'        => $summit->url_title,
                    'description'      => $summit->description,
                    'ka_description'   => $summit->ka_description,
                    'height'           => $summit->height,
                    'latitude'         => $summit->latitude,
                    'longitude'        => $summit->longitude,
                    'region_id'        => $summit->region_id,
                    'region_name'      => $summit->region?->us_name,
                    'mount_route_id'   => $summit->mount_route_id,
                    'mount_route_name' => $mr_name,
                    'qr_code'          => $summit->qr_code,
                    'published'        => $summit->published,
                    'created_at'       => $summit->created_at,
                ];
            });

        return response()->json($summits);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'          => 'required|string|max:255',
            'ka_title'       => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'ka_description' => 'nullable|string',
            'height'         => 'nullable|integer|min:0',
            'latitude'       => 'nullable|numeric|between:-90,90',
            'longitude'      => 'nullable|numeric|between:-180,180',
            'mount_route_id' => 'nullable|integer|exists:articles,id',
            'published'      => 'nullable|boolean',
        ]);

        $summit = Summit::create([
            'title'          => $request->title,
            'ka_title'       => $request->ka_title,
            'url_title'      => $this->generateUniqueUrlTitle($request->title),
            'description'    => $request->description,
            'ka_description' => $request->ka_description,
            'height'         => $request->height,
            'latitude'       => $request->latitude,
            'longitude'      => $request->longitude,
            'mount_route_id' => $request->mount_route_id,
            'published'      => $request->published ?? false,
        ]);

        return response()->json($summit->fresh()->load('mountRoute'), 201);
    }

    public function update(Request $request, $id)
    {
        $summit = Summit::findOrFail($id);

        $request->validate([
            'title'          => 'sometimes|required|string|max:255',
            'ka_title'       => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'ka_description' => 'nullable|string',
            'height'         => 'nullable|integer|min:0',
            'latitude'       => 'nullable|numeric|between:-90,90',
            'longitude'      => 'nullable|numeric|between:-180,180',
            'mount_route_id' => 'nullable|integer|exists:articles,id',
            'published'      => 'nullable|boolean',
        ]);

        $data = $request->only([
            'title', 'ka_title', 'description', 'ka_description',
            'height', 'latitude', 'longitude', 'mount_route_id', 'published'
        ]);

        if (isset($data['title']) && $data['title'] !== $summit->title) {
            $data['url_title'] = $this->generateUniqueUrlTitle($data['title'], $summit->id);
        }

        $summit->update($data);

        return response()->json($summit->fresh()->load('mountRoute'));
    }

    public function destroy($id)
    {
        Summit::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function save_qr(Request $request, $id)
    {
        $request->validate(['qr_code' => 'required|string']);
        $summit = Summit::findOrFail($id);
        $summit->update(['qr_code' => $request->qr_code]);
        return response()->json(['message' => 'QR code saved']);
    }

    public function get_mount_routes()
    {
        $articles = Article::where('category', 'mount_route')
            ->where('published', 1)
            ->with(['global_article_us', 'global_article_ka'])
            ->get()
            ->map(fn($a) => [
                'id'   => $a->id,
                'name' => $a->global_article_us?->title ?? $a->global_article_ka?->title ?? $a->url_title,
            ])
            ->sortBy('name')
            ->values();

        return response()->json($articles);
    }

    public function get_regions()
    {
        return response()->json(Region::orderBy('us_name')->get(['id', 'us_name', 'ka_name']));
    }

    public function get_ascents($id)
    {
        $summit = Summit::findOrFail($id);

        $ascents = $summit->ascents()
            ->with(['users.user', 'ascentRoutes'])
            ->orderBy('ascent_date', 'desc')
            ->get()
            ->map(function ($ascent) {
                return [
                    'id'               => $ascent->id,
                    'name'             => $ascent->name,
                    'surname'          => $ascent->surname,
                    'email'            => $ascent->email,
                    'comment'          => $ascent->comment,
                    'photo'            => $ascent->photo,
                    'is_gps_validated' => $ascent->is_gps_validated,
                    'ascent_date'      => $ascent->ascent_date,
                    'other_route'      => $ascent->other_route,
                    'matched_users'    => $ascent->users->map(fn($u) => [
                        'id'      => $u->user?->id,
                        'name'    => $u->user?->name,
                        'surname' => $u->user?->surname,
                    ]),
                ];
            });

        return response()->json(['summit' => ['id' => $summit->id, 'title' => $summit->title], 'ascents' => $ascents]);
    }

    public function get_all_ascents()
    {
        $ascents = SummitAscent::with(['summit', 'users.user', 'ascentRoutes.route.sector'])
            ->orderBy('ascent_date', 'desc')
            ->get()
            ->map(function ($ascent) {
                $routeName       = null;
                $routeGrade      = null;
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
                    'summit_id'        => $ascent->summit_id,
                    'summit_title'     => $ascent->summit?->title,
                    'summit_url'       => $ascent->summit?->url_title,
                    'name'             => $ascent->name,
                    'surname'          => $ascent->surname,
                    'email'            => $ascent->email,
                    'comment'          => $ascent->comment,
                    'photo'            => $ascent->photo,
                    'is_gps_validated' => $ascent->is_gps_validated,
                    'ascent_date'      => $ascent->ascent_date,
                    'route_name'       => $routeName,
                    'route_grade'      => $routeGrade,
                    'route_article_url'=> $routeArticleUrl,
                    'matched_users'    => $ascent->users->map(fn($u) => [
                        'id'      => $u->user?->id,
                        'name'    => $u->user?->name,
                        'surname' => $u->user?->surname,
                    ]),
                ];
            });

        return response()->json($ascents);
    }

    private function generateUniqueUrlTitle(string $title, int $excludeId = null): string
    {
        $base  = Str::slug($title);
        $slug  = $base;
        $count = 1;

        while (Summit::where('url_title', $slug)->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))->exists()) {
            $slug = $base . '-' . $count++;
        }

        return $slug;
    }
}

<?php

namespace App\Http\Controllers\Api\User\Admin\Summit;

use App\Http\Controllers\Controller;
use App\Services\PermissionService;
use App\Services\URLTitleService;
use Illuminate\Http\Request;

use App\Models\Summit\Summit;
use App\Models\Summit\SummitAscent;
use App\Models\Summit\SummitMountRoute;
use App\Models\Guide\Article;
use App\Models\Guide\Locale_article;
use App\Services\Abstract\ImageControllService;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\ErrorCorrectionLevel;

class SummitController extends Controller
{
    public function index()
    {
        if ($auth = PermissionService::authorize('summit', 'show')) return $auth;

        $summits = Summit::with(['mountRoutes.article.global_article_us', 'mountRoutes.article.global_article_ka'])
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($summit) {
                $routes = $summit->mountRoutes->map(function ($smr) {
                    $a = $smr->article;
                    return [
                        'id'   => $a?->id,
                        'name' => $a?->global_article_us?->title ?? $a?->global_article_ka?->title ?? $a?->url_title,
                    ];
                })->filter(fn($r) => $r['id'])->values();

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
                    'mount_route_ids'       => $routes->pluck('id'),
                    'mount_route_names'     => $routes->pluck('name'),
                    'mount_routes_display'  => $routes->pluck('name')->implode(', '),
                    'qr_code'          => $summit->qr_code,
                    'published'        => $summit->published,
                    'created_at'       => $summit->created_at,
                ];
            });

        return response()->json($summits);
    }

    public function store(Request $request)
    {
        if ($auth = PermissionService::authorize('summit', 'add')) return $auth;

        $request->validate([
            'title'          => 'required|string|max:255',
            'ka_title'       => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'ka_description' => 'nullable|string',
            'height'         => 'nullable|integer|min:0',
            'latitude'       => 'nullable|numeric|between:-90,90',
            'longitude'      => 'nullable|numeric|between:-180,180',
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
            'published'      => $request->published ?? false,
        ]);

        if ($request->hasFile('image')) {
            $summit->update(['image' => ImageControllService::image_upload('images/summit_img/', $request, 'image', 1)]);
        }

        return response()->json($summit->fresh(), 201);
    }

    public function update(Request $request, $id)
    {
        if ($auth = PermissionService::authorize('summit', 'edit')) return $auth;

        $summit = Summit::findOrFail($id);

        $request->validate([
            'title'               => 'sometimes|required|string|max:255',
            'ka_title'            => 'nullable|string|max:255',
            'description'         => 'nullable|string',
            'ka_description'      => 'nullable|string',
            'height'              => 'nullable|integer|min:0',
            'latitude'            => 'nullable|numeric|between:-90,90',
            'longitude'           => 'nullable|numeric|between:-180,180',
            'published'           => 'nullable|boolean',
            'url_title'           => 'nullable|string|max:255|unique:summits,url_title,' . $id,
        ]);

        $data = $request->only([
            'title', 'ka_title', 'description', 'ka_description',
            'height', 'latitude', 'longitude', 'published'
        ]);

        if ($request->boolean('is_change_url_title')) {
            $data['url_title'] = $this->generateUniqueUrlTitle($request->input('title', $summit->title), $summit->id);
        } elseif ($request->filled('url_title')) {
            $data['url_title'] = $request->url_title;
        }

        if ($request->hasFile('image')) {
            $data['image'] = ImageControllService::image_update('images/summit_img/', $summit, $request, 'image', 'image', 1);
        }

        $summit->update($data);

        return response()->json($summit->fresh());
    }

    public function destroy($id)
    {
        if ($auth = PermissionService::authorize('summit', 'del')) return $auth;

        Summit::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function update_coordinates(Request $request, $id)
    {
        if ($auth = PermissionService::authorize('summit', 'edit')) return $auth;

        $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $summit = Summit::findOrFail($id);
        $summit->update([
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json([
            'message'   => 'Coordinates updated',
            'latitude'  => $summit->latitude,
            'longitude' => $summit->longitude,
        ]);
    }

    public function save_qr(Request $request, $id)
    {
        if ($auth = PermissionService::authorize('summit', 'edit')) return $auth;

        $request->validate(['qr_code' => 'required|string']);
        $summit = Summit::findOrFail($id);
        $summit->update(['qr_code' => $request->qr_code]);
        return response()->json(['message' => 'QR code saved']);
    }

    public function export_laser_plate($id)
    {
        if ($auth = PermissionService::authorize('summit', 'show')) return $auth;

        $summit = Summit::findOrFail($id);

        $appSsh = rtrim(config('app.app_ssh'), '/');
        $summitUrl = trim(env('SUMMIT_URL', 'summit.climbing.ge'), '/');
        $qrValue = $summit->qr_code ?: ($appSsh . '/' . $summitUrl . '/make_ascent/' . $summit->id);

        $qrResult = Builder::create()
            ->data($qrValue)
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(600)
            ->margin(10)
            ->build();

        // Page size (200mm x 120mm) is set via the view's own @page CSS rule.
        // Calling setPaper() here as well conflicts with it and produces a
        // spurious blank second page, so the size is left to the view.
        $pdf = Pdf::loadView('pdf.summit_laser_plate', [
            'summit'   => $summit,
            'qr_data_uri' => $qrResult->getDataUri(),
        ]);

        $filename = ($summit->url_title ?: 'summit') . '-laser-plate.pdf';

        return $pdf->download($filename);
    }

    public function get_summit_mount_routes($summit_id)
    {
        if ($auth = PermissionService::authorize('summit', 'show')) return $auth;

        $summit = Summit::findOrFail($summit_id);

        $routes = SummitMountRoute::where('summit_id', $summit_id)
            ->with(['article.global_article_us', 'article.global_article_ka'])
            ->get()
            ->map(fn($smr) => [
                'id'         => $smr->id,
                'article_id' => $smr->article_id,
                'name'       => $smr->article?->global_article_us?->title
                    ?? $smr->article?->global_article_ka?->title
                    ?? $smr->article?->url_title,
            ]);

        return response()->json([
            'summit' => ['id' => $summit->id, 'title' => $summit->title],
            'routes' => $routes,
        ]);
    }

    public function add_mount_route_relation(Request $request)
    {
        if ($auth = PermissionService::authorize('summit', 'edit')) return $auth;

        $request->validate([
            'summit_id'  => 'required|integer|exists:summits,id',
            'article_id' => 'required|integer|exists:articles,id',
        ]);

        // Reassign if already linked to another summit
        SummitMountRoute::where('article_id', $request->article_id)->delete();

        $relation = SummitMountRoute::create([
            'summit_id'  => $request->summit_id,
            'article_id' => $request->article_id,
        ]);

        $relation->load(['article.global_article_us', 'article.global_article_ka']);

        return response()->json([
            'id'         => $relation->id,
            'article_id' => $relation->article_id,
            'name'       => $relation->article?->global_article_us?->title
                ?? $relation->article?->global_article_ka?->title
                ?? $relation->article?->url_title,
        ], 201);
    }

    public function remove_mount_route_relation($id)
    {
        if ($auth = PermissionService::authorize('summit', 'edit')) return $auth;

        SummitMountRoute::findOrFail($id)->delete();

        return response()->json(['message' => 'Removed']);
    }

    public function get_mount_routes()
    {
        if ($auth = PermissionService::authorize('summit', 'show')) return $auth;

        $assigned = SummitMountRoute::pluck('summit_id', 'article_id');

        $articles = Article::where('category', 'mount_route')
            ->with([
                'global_article_us',
                'global_article_ka',
                'mount_masiv.us_mount',
                'mount_masiv.ka_mount',
            ])
            ->get()
            ->map(function ($a) use ($assigned) {
                $mount      = $a->mount_masiv->first();
                $mountName  = $mount
                    ? ($mount->us_mount?->title ?? $mount->ka_mount?->title ?? null)
                    : null;

                return [
                    'id'         => $a->id,
                    'name'       => $a->global_article_us?->title ?? $a->global_article_ka?->title ?? $a->url_title,
                    'summit_id'  => $assigned[$a->id] ?? null,
                    'published'  => (bool) $a->published,
                    'mount_id'   => $mount?->id,
                    'mount_name' => $mountName,
                ];
            })
            ->sortBy('name')
            ->values();

        return response()->json($articles);
    }

    private function syncMountRoutes(int $summitId, array $articleIds): void
    {
        // Remove relations that are no longer selected
        SummitMountRoute::where('summit_id', $summitId)
            ->whereNotIn('article_id', $articleIds)
            ->delete();

        // Add new relations (skip if the article is already linked to another summit)
        $existing = SummitMountRoute::where('summit_id', $summitId)->pluck('article_id')->toArray();

        foreach ($articleIds as $articleId) {
            if (in_array($articleId, $existing)) continue;
            // Remove from other summit if assigned elsewhere
            SummitMountRoute::where('article_id', $articleId)->delete();
            SummitMountRoute::create(['summit_id' => $summitId, 'article_id' => $articleId]);
        }
    }

    public function get_summits_list()
    {
        if ($auth = PermissionService::authorize('summit', 'show')) return $auth;

        $summits = Summit::orderBy('title')->get(['id', 'title', 'ka_title', 'url_title']);

        return response()->json($summits);
    }

    public function get_article_summit_relation($article_id)
    {
        if ($auth = PermissionService::authorize('summit', 'show')) return $auth;

        $relation = SummitMountRoute::where('article_id', $article_id)
            ->with('summit')
            ->first();

        if (!$relation) {
            return response()->json(null);
        }

        return response()->json([
            'relation_id' => $relation->id,
            'summit_id'   => $relation->summit_id,
            'summit_title' => $relation->summit->title ?? $relation->summit->ka_title ?? null,
        ]);
    }

    public function get_ascents($id)
    {
        if ($auth = PermissionService::authorize('summit', 'show')) return $auth;

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
        if ($auth = PermissionService::authorize('summit', 'show')) return $auth;

        $ascents = SummitAscent::with([
                'summit', 'users.user',
                'ascentRoutes.article.global_article_us',
                'ascentRoutes.article.global_article_ka',
            ])
            ->orderBy('ascent_date', 'desc')
            ->get()
            ->map(function ($ascent) {
                $routeName       = null;
                $routeGrade      = null;
                $routeArticleUrl = null;

                if ($ascent->ascentRoutes->isNotEmpty()) {
                    $ar = $ascent->ascentRoutes->first();
                    if ($ar->article) {
                        $routeName       = $ar->article->global_article_us?->title
                            ?? $ar->article->global_article_ka?->title
                            ?? $ar->article->url_title;
                        $routeGrade      = $ar->article->mount_grade ?: null;
                        $routeArticleUrl = $ar->article->url_title;
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
        $base  = URLTitleService::get_url_title($title);
        $slug  = $base;
        $count = 1;

        while (Summit::where('url_title', $slug)->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))->exists()) {
            $slug = $base . '_' . $count++;
        }

        return $slug;
    }
}

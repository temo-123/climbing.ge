<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Summit\SummitAscentRoute;
use Illuminate\Http\Request;

class SummitController extends Controller
{
    public function get_all_my_ascents() {
        $auth_user = auth()->user();

        $ascents = $auth_user->ascents()
            ->with([
                'summit',
                'ascentRoutes.article.global_article_us',
                'ascentRoutes.article.global_article_ka',
            ])
            ->get()
            ->map(function ($ascent) {
                $routeName  = null;
                $routeGrade = null;
                $articleId  = null;
                $otherRoute = null;

                if ($ascent->ascentRoutes->isNotEmpty()) {
                    $ar = $ascent->ascentRoutes->first();
                    if ($ar->article) {
                        $routeName  = $ar->article->global_article_us?->title
                            ?? $ar->article->global_article_ka?->title
                            ?? $ar->article->url_title;
                        $routeGrade = $ar->article->mount_grade ?: null;
                        $articleId  = $ar->article_id;
                    } elseif ($ar->other_route_name) {
                        $routeName  = $ar->other_route_name;
                        $otherRoute = $ar->other_route_name;
                    }
                }

                return [
                    'id'               => $ascent->id,
                    'summit'           => $ascent->summit ? [
                        'id'        => $ascent->summit->id,
                        'title'     => $ascent->summit->title,
                        'url_title' => $ascent->summit->url_title,
                    ] : null,
                    'name'             => $ascent->name,
                    'surname'          => $ascent->surname,
                    'email'            => $ascent->email,
                    'comment'          => $ascent->comment,
                    'photo'            => $ascent->photo,
                    'is_gps_validated' => $ascent->is_gps_validated,
                    'ascent_date'      => $ascent->ascent_date,
                    'ascent_time'      => $ascent->ascent_time,
                    'user_latitude'    => $ascent->user_latitude,
                    'user_longitude'   => $ascent->user_longitude,
                    'route_name'       => $routeName,
                    'route_grade'      => $routeGrade,
                    'article_id'       => $articleId,
                    'other_route'      => $otherRoute,
                ];
            });

        return response()->json($ascents);
    }

    public function update_ascent(Request $request, $id) {
        $auth_user = auth()->user();
        $ascent = $auth_user->ascents()->find($id);
        if (!$ascent) {
            return response()->json(['message' => 'Ascent not found'], 404);
        }

        $request->validate([
            'comment'     => 'nullable|string',
            'article_id'  => 'nullable|integer|exists:articles,id',
            'other_route' => 'nullable|string|max:255',
        ]);

        $ascent->update(['comment' => $request->comment]);

        if ($request->filled('article_id')) {
            SummitAscentRoute::updateOrCreate(
                ['ascent_id' => $ascent->id],
                ['article_id' => $request->article_id, 'other_route_name' => null]
            );
        } elseif ($request->filled('other_route')) {
            SummitAscentRoute::updateOrCreate(
                ['ascent_id' => $ascent->id],
                ['article_id' => null, 'other_route_name' => $request->other_route]
            );
        } else {
            SummitAscentRoute::where('ascent_id', $ascent->id)->delete();
        }

        return response()->json(['message' => 'Ascent updated successfully']);
    }

    public function del_ascent($id) {
        $auth_user = auth()->user();
        $ascent = $auth_user->ascents()->find($id);
        if ($ascent) {
            $ascent->delete();
            return response()->json(['message' => 'Ascent deleted successfully']);
        }
        return response()->json(['message' => 'Ascent not found'], 404);
    }
}

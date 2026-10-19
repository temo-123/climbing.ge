<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guide\RouteAscent;
use App\Models\Guide\Route;
use App\Services\Abstract\ImageControllService;
use App\Services\PermissionService;
use App\Services\ReCaptchaV3Service;
use Illuminate\Support\Facades\File;

class RouteAscentController extends Controller
{
    public function create_route_ascent(Request $request, $route_id)
    {
        $captcha = new ReCaptchaV3Service();
        if ($captcha->isConfigured()) {
            $token = $request->input('recaptcha_token');
            if (!$token || !$captcha->verifySmart($token, $request->ip())) {
                return response()->json(['message' => 'reCAPTCHA verification failed. Please try again.'], 422);
            }
        }

        $route = Route::findOrFail($route_id);

        $request->validate([
            'ascent_date'     => 'required|date|before_or_equal:today',
            'ascent_style'    => 'required|in:' . implode(',', RouteAscent::STYLES),
            'attempts'        => 'nullable|integer|min:1|max:999',
            'suggested_grade' => 'nullable|string|max:32',
            'comment'         => 'nullable|string|max:2000',
            'photo'           => 'nullable|image|max:10240',
        ]);

        // Onsight / flash are first-try by definition
        $attempts = in_array($request->ascent_style, ['onsight', 'flash']) ? 1 : $request->attempts;

        File::ensureDirectoryExists(public_path('images/route_ascents_img'));

        $ascent = RouteAscent::create([
            'route_id'        => $route->id,
            'user_id'         => $request->user()->id,
            'ascent_date'     => $request->ascent_date,
            'ascent_style'    => $request->ascent_style,
            'attempts'        => $attempts,
            'suggested_grade' => $request->suggested_grade ? strip_tags($request->suggested_grade) : null,
            'comment'         => $request->comment,
            'photo'           => ImageControllService::image_upload('images/route_ascents_img/', $request, 'photo', 2),
        ]);

        return response()->json(['message' => 'Ascent recorded successfully', 'ascent_id' => $ascent->id], 201);
    }

    public function get_my_route_ascents(Request $request)
    {
        return RouteAscent::where('user_id', $request->user()->id)
            ->with('route:id,name,grade,or_grade,category,sector_id', 'route.sector:id,name')
            ->orderByDesc('ascent_date')
            ->orderByDesc('id')
            ->get()
            ->map(fn ($ascent) => array_merge($ascent->toArray(), [
                'route_name'  => $ascent->route?->name,
                'route_grade' => $ascent->route?->or_grade ? $ascent->route->grade . ' / ' . $ascent->route->or_grade : $ascent->route?->grade,
                'sector_name' => $ascent->route?->sector?->name,
            ]));
    }

    public function del_my_route_ascent(Request $request, $ascent_id)
    {
        $ascent = RouteAscent::where('id', $ascent_id)->where('user_id', $request->user()->id)->first();
        if (!$ascent) return response()->json(['error' => 'Not found'], 404);

        $this->delete_ascent($ascent);

        return response()->json(['success' => true]);
    }

    public function get_all_route_ascents_admin()
    {
        $auth = PermissionService::authorize('route', 'show');
        if ($auth) return $auth;

        return RouteAscent::with(['route:id,name,grade', 'user:id,name,surname,email'])
            ->latest('id')
            ->get()
            ->map(fn ($ascent) => array_merge($ascent->toArray(), [
                'route_name'  => $ascent->route?->name,
                'route_grade' => $ascent->route?->grade,
                'user_name'   => trim(($ascent->user?->name ?? '') . ' ' . ($ascent->user?->surname ?? '')),
                'user_email'  => $ascent->user?->email,
            ]));
    }

    public function del_route_ascent_admin($ascent_id)
    {
        $auth = PermissionService::authorize('route', 'del');
        if ($auth) return $auth;

        $ascent = RouteAscent::find($ascent_id);
        if (!$ascent) return response()->json(['error' => 'Not found'], 404);

        $this->delete_ascent($ascent);

        return response()->json(['success' => true]);
    }

    private function delete_ascent(RouteAscent $ascent)
    {
        if ($ascent->photo) {
            ImageControllService::image_delete('images/route_ascents_img/', $ascent, 'photo');
        }
        $ascent->delete();
    }
}

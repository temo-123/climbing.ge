<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

/**
 * Users younger than User::MIN_AGE (13) can register and log in, but every
 * account function is closed: every POST / PUT / PATCH / DELETE under api/ is
 * refused except the few endpoints in ALWAYS_OPEN (log in / out, password
 * reset, delete the account, contact support, read-style POSTs used while
 * browsing). The birthday itself can't be changed by the user while underage
 * (Options is closed) — an admin can correct it.
 *
 * Refusal: 403 { code: 'age_restricted', min_age: 13 }. The frontend
 * (bootstrap.js) turns it into the age-alert modal; on user.climbing.ge the
 * route guard (app.js) sends underage users to the age page before they reach
 * any function.
 *
 * Runs in the `api` group, so it also covers dual-flow (guest-or-user) routes:
 * a request without a token (guest) is never affected.
 *
 * Closed by default: a new write endpoint is gated automatically.
 * Docs: docs/FUNCTIONS_DOCS/global/age_restriction.md
 */
class EnsureMinimumAgeForActions
{
    const GATED_METHODS = ['POST', 'PUT', 'PATCH', 'DELETE'];

    const ALWAYS_OPEN = [
        // auth & leaving
        'api/login', 'api/login/*', 'api/register', 'api/password/*',
        'api/logout',
        'api/account/delete',

        // read-style POSTs used while browsing
        'api/*search*', 'api/*Search*',
        'api/get_article/get_similar_locale_article/*',
        'api/get_route/routes_authers_by_categories',
        'api/get_sector/sectors_and_routes_quantity_by_categories',
        'api/get_bisnes/track/*',
        'api/get_general_info/track_action',
        'api/set_general_info/track_action',

        // support must stay reachable (e.g. a wrong date of birth)
        'api/message',
    ];

    public function handle(Request $request, Closure $next)
    {
        if (!in_array($request->method(), self::GATED_METHODS, true) || !$request->is('api/*')) {
            return $next($request);
        }
        if ($request->is(...self::ALWAYS_OPEN)) {
            return $next($request);
        }

        // sanctum guard: these routes are often public (guest-or-user), where
        // the default guard would never see the Bearer token
        $user = auth('sanctum')->user();

        if ($user && $user->isUnderage()) {
            return response()->json(self::payload(), 403);
        }

        return $next($request);
    }

    public static function payload(): array
    {
        $message = 'You must be at least ' . User::MIN_AGE . ' years old to use climbing.ge account functions.';
        return [
            'code'    => 'age_restricted',
            'min_age' => User::MIN_AGE,
            'message' => $message,
            'alert'   => [
                'type'    => 'warning',
                'title'   => 'Age restriction',
                'message' => $message,
                'icon'    => 'user',
            ],
        ];
    }
}

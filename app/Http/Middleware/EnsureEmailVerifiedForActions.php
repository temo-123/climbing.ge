<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Logged-in users with an unverified e-mail can browse (GET) everything, but
 * every write is closed — comments, reviews, ascents, follows, favorites,
 * orders, the whole user panel / admin CMS — except the few endpoints in
 * ALWAYS_OPEN: account management (so the user can fix the address or leave),
 * read-style POSTs (searches, price quotes), removing your own things,
 * donations and the training-app sync.
 *
 * Refusal: 403 { code: 'email_not_verified' }. The frontend (bootstrap.js)
 * turns it into the "verify your e-mail" modal with a resend button; on
 * user.climbing.ge the route guard (app.js) sends unverified users to the
 * "verify your e-mail" page before they reach any function.
 *
 * Runs in the `api` group, so it also covers dual-flow (guest-or-user) routes:
 * a request without a token (guest) is never affected.
 *
 * Closed by default: a new write endpoint is gated automatically. Add it to
 * ALWAYS_OPEN only if an unverified user really needs it.
 * Docs: docs/FUNCTIONS_DOCS/global/email_verification.md
 */
class EnsureEmailVerifiedForActions
{
    const GATED_METHODS = ['POST', 'PUT', 'PATCH', 'DELETE'];

    const ALWAYS_OPEN = [
        // auth & account management
        'api/login', 'api/login/*', 'api/register', 'api/password/*',
        'api/logout',
        'api/account/delete',
        'api/get_options/*',                   // profile, e-mail change, notifications, preferences
        'api/user/update_password',
        'api/user/user_image_update/*',
        'api/add_user_adreses', 'api/edit_adres/*', 'api/get_editing_adres/*', 'api/set_default_adres/*', 'api/del_user_adreses/*',
        'api/user_site', 'api/user_site/*',     // links on the own profile
        'api/strava/disconnect',

        // read-style POSTs used while browsing
        'api/*search*', 'api/*Search*',
        'api/get_article/get_similar_locale_article/*',
        'api/get_route/routes_authers_by_categories',
        'api/get_sector/sectors_and_routes_quantity_by_categories',
        'api/get_bisnes/track/*',
        'api/get_general_info/track_action',
        'api/set_general_info/track_action',
        'api/set_order/checkout_quote',
        'api/set_order/check_sale_code',
        'api/set_wall_calculator/export_pdf',
        'api/save-canvas-image',

        // order review from the "order complete" e-mail — the e-mailed token proves the address
        'api/order_review/save/*',

        // cart (checkout itself is closed)
        'api/cart/*',

        // removing / hiding your own things
        'api/set_user_follow/unfollow/*',
        'api/set_follow/del_follower/*',
        'api/set_faworite/*',
        'api/del_from_favorite/*',
        'api/stock_notification/unsubscribe/*',
        'api/get_user_ascents/del_ascent/*',
        'api/get_user_ascents/bulk_delete',
        'api/summit_track/*',                  // only DELETE {ascent_id} — gpx / strava uploads are below
        'api/set_article/set_guide_comment/user_hide_comment/*',
        'api/set_article/set_guide_comment/user_show_comment/*',
        'api/set_article/set_guide_comment/user_bulk_del_comment',
        'api/set_route/set_route_review/user_hide_review/*',
        'api/set_route/set_route_review/user_show_review/*',
        'api/set_mtp_review/user_hide_mtp_review/*',
        'api/set_mtp_review/user_show_mtp_review/*',
        'api/set_area_report/del_my_area_report/*',

        // contact & money: support must stay reachable, donations aren't an account feature
        'api/message',
        'api/set_donation/*',
        'api/payment/*/callback',

        // e-mail links for guest flows (no account needed)
        'api/set_guide_comment/confirm_email/*',
        'api/set_product_feedback_by_gest/confirm_email/*',
        'api/set_user_reservation/verify_reservation/*',
        'api/set_user_reservation/reservation_status/*',

        // training mobile app — blocking would lose workouts logged offline
        'api/user_training/sync',
    ];

    // matched by ALWAYS_OPEN wildcards above, but they create content
    const NEVER_OPEN = [
        'api/summit_track/gpx/*',
        'api/summit_track/strava/*',
    ];

    public function handle(Request $request, Closure $next)
    {
        if (!in_array($request->method(), self::GATED_METHODS, true) || !$request->is('api/*')) {
            return $next($request);
        }
        if ($request->is(...self::ALWAYS_OPEN) && !$request->is(...self::NEVER_OPEN)) {
            return $next($request);
        }

        // sanctum guard: these routes are often public (guest-or-user), where
        // the default guard would never see the Bearer token
        $user = auth('sanctum')->user();

        if ($user && !$user->hasVerifiedEmail()) {
            return response()->json(self::payload($user), 403);
        }

        return $next($request);
    }

    public static function payload($user): array
    {
        return [
            'code'    => 'email_not_verified',
            'email'   => $user->email,
            'message' => 'Please verify your e-mail address to use this feature.',
            'alert'   => [
                'type'    => 'warning',
                'title'   => 'E-mail verification required',
                'message' => 'Please verify your e-mail address to use this feature.',
                'icon'    => 'envelope',
            ],
        ];
    }
}

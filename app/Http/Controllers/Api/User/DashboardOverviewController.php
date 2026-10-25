<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\PermissionService;

/**
 * Data for the user panel home page (HomePageComponent):
 *  - get_my_overview: the climber's own numbers, recent ascents, upcoming
 *    events, profile checklist — every logged-in user.
 *  - get_admin_overview: "needs attention" counters and 30-day activity,
 *    each block only when the user has the permission for its admin page.
 */
class DashboardOverviewController extends Controller
{
    public function get_my_overview(Request $request)
    {
        $user = $request->user();
        $id = $user->id;

        $route_ascents = DB::table('route_ascents')->where('user_id', $id);
        $recent = DB::table('route_ascents')
            ->join('routes', 'routes.id', '=', 'route_ascents.route_id')
            ->leftJoin('sectors', 'sectors.id', '=', 'routes.sector_id')
            ->where('route_ascents.user_id', $id)
            ->orderByDesc('route_ascents.ascent_date')->orderByDesc('route_ascents.id')
            ->limit(5)
            ->get(['route_ascents.id', 'route_ascents.ascent_date', 'route_ascents.ascent_style', 'routes.name as route_name', 'routes.grade', 'sectors.name as sector_name']);

        $events = DB::table('interested_events')
            ->join('events', 'events.id', '=', 'interested_events.event_id')
            ->leftJoin('locale_events', 'locale_events.id', '=', $user->lang === 'ka' ? 'events.ka_event_id' : 'events.us_event_id')
            ->where('interested_events.user_id', $id)
            ->where('events.published', 1)
            ->where(fn ($q) => $q->where('events.end_data', '>=', now())->orWhere(fn ($q2) => $q2->whereNull('events.end_data')->where('events.start_data', '>=', now()->startOfDay())))
            ->orderBy('events.start_data')
            ->limit(3)
            ->get(['events.id', 'events.url_title', 'events.start_data', 'events.end_data', 'locale_events.title']);

        $last_order = DB::table('orders')->where('user_id', $id)->latest('id')->first(['id', 'status', 'created_at']);

        $social = is_array($user->social_links) ? array_filter($user->social_links) : [];

        return response()->json([
            'counts' => [
                'route_ascents'  => (clone $route_ascents)->count(),
                'routes_this_year' => (clone $route_ascents)->whereYear('ascent_date', now()->year)->count(),
                'mtp_ascents'    => DB::table('mtp_ascents')->where('user_id', $id)->count(),
                'summit_ascents' => DB::table('summit_ascent_users')->where('user_id', $id)->count(),
                'reviews'        => DB::table('sport_route_reviews')->where('user_id', $id)->count() + DB::table('mtp_reviews')->where('user_id', $id)->count(),
                'favorites'      => DB::table('favorite_outdoor_areas')->where('user_id', $id)->count(),
                'favorite_products' => DB::table('favorite_products')->where('user_id', $id)->count(),
                'orders'         => DB::table('orders')->where('user_id', $id)->count(),
                'partner_posts'  => DB::table('partner_posts')->where('user_id', $id)->whereDate('climb_date', '>=', now()->toDateString())->count(),
            ],
            'recent_ascents' => $recent,
            'upcoming_events' => $events,
            'last_order' => $last_order,
            // profile checklist — the page shows what's still missing
            'profile' => [
                'photo'       => !empty($user->image),
                'bio'         => !empty(trim((string) $user->my_bio)),
                'location'    => !empty($user->city) || !empty($user->country),
                'social'      => count($social) > 0,
                'preferences' => !empty($user->onboarded_at) || !empty($user->grade) || !empty($user->mount_grade),
                'first_ascent' => (clone $route_ascents)->exists() || DB::table('summit_ascent_users')->where('user_id', $id)->exists(),
                'verified'    => !empty($user->email_verified_at),
            ],
        ]);
    }

    public function get_admin_overview(Request $request)
    {
        $week = now()->subDays(7);
        $blocks = [];

        if (PermissionService::checkPermission('article', 'show')) {
            $blocks['guide'] = [
                'draft_articles'    => DB::table('articles')->where('published', 0)->count(),
                'hazard_reports'    => DB::table('area_condition_reports')->whereIn('condition', ['hazard', 'closure'])->where('admin_hidden', false)->where('created_at', '>=', now()->subDays(30))->count(),
                'condition_reports' => DB::table('area_condition_reports')->where('created_at', '>=', $week)->count(),
                'partner_posts'     => DB::table('partner_posts')->where('admin_hidden', false)->whereDate('climb_date', '>=', now()->toDateString())->count(),
            ];
        }
        if (PermissionService::checkPermission('comment', 'show')) {
            $blocks['moderation'] = [
                'comment_complaints' => DB::table('article_comment_complaints')->count(),
                'new_comments'       => DB::table('article_comments')->where('created_at', '>=', $week)->whereNull('deleted_at')->count(),
                'new_reviews'        => DB::table('sport_route_reviews')->where('created_at', '>=', $week)->count() + DB::table('mtp_reviews')->where('created_at', '>=', $week)->count(),
                'user_complaints'    => DB::table('user_complaints')->whereNull('decision')->count(),
            ];
        }
        if (PermissionService::checkPermission('order', 'show')) {
            $open = ['Treatment', 'Preparation for shipment', 'Ready to ship'];
            $blocks['shop'] = [
                'open_orders'   => DB::table('orders')->where(fn ($q) => $q->whereIn('status', $open)->orWhereNull('status'))->where('created_at', '>=', now()->subDays(90))->count(),
                'orders_week'   => DB::table('orders')->where('created_at', '>=', $week)->count(),
                'revenue_week'  => round((float) DB::table('orders')->where('created_at', '>=', $week)->sum('payment_amount'), 2),
                'back_in_stock_waiting' => DB::table('stock_notifications')->whereNull('notified_at')->count(),
            ];
        }
        if (PermissionService::checkPermission('user', 'show')) {
            $blocks['users'] = [
                'total'      => DB::table('users')->count(),
                'new_week'   => DB::table('users')->where('created_at', '>=', $week)->count(),
                'active_week' => DB::table('users')->where('last_seen_at', '>=', $week)->count(),
            ];
        }

        // 30-day activity sparklines (only for what this admin can see)
        $series = [];
        $daily = function ($table, $column = 'created_at') {
            $rows = DB::table($table)->where($column, '>=', now()->subDays(29)->startOfDay())
                ->selectRaw("DATE($column) as d, COUNT(*) as n")->groupBy('d')->pluck('n', 'd');
            $out = [];
            for ($i = 29; $i >= 0; $i--) $out[] = (int) ($rows[now()->subDays($i)->toDateString()] ?? 0);
            return $out;
        };
        if (isset($blocks['users'])) $series['users'] = $daily('users');
        if (isset($blocks['guide'])) $series['ascents'] = $daily('route_ascents');
        if (isset($blocks['moderation'])) $series['comments'] = $daily('article_comments');
        if (isset($blocks['shop'])) $series['orders'] = $daily('orders');

        return response()->json(['blocks' => $blocks, 'series' => $series]);
    }
}

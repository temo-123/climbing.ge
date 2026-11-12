<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Read-only, everything-about-one-user snapshot for the admin Users list
 * "user info" modal (docs/FUNCTIONS_DOCS/user/admin_user_info.md).
 *
 * Every table that references a user is covered. Each block returns a total
 * `count` plus the latest LIMIT `items`, so the modal shows real totals while
 * staying light. Secrets are never selected: no password / remember_token,
 * no Sanctum token hashes, no Strava access / refresh tokens.
 *
 * Each section runs in its own try/catch so a missing table (e.g. training
 * tables on an environment that never ran those migrations) blanks only that
 * section instead of failing the whole modal.
 */
class AdminUserOverviewService
{
    const LIMIT = 15;

    public static function build(int $user_id): ?array
    {
        $user = User::with(['role:id,name,slug', 'permissions:id,subject,action'])
            ->withPointsCounts()
            ->find($user_id);
        if (!$user) return null;

        $s = fn(string $name, callable $fn) => self::section($name, $fn);

        return [
            'user'      => self::profile($user),
            'account'   => $s('account',   fn() => self::account($user)),
            'points'    => $s('points',    fn() => self::points($user)),
            'activity'  => $s('activity',  fn() => self::activity($user_id)),
            'shop'      => $s('shop',      fn() => self::shop($user_id)),
            'favorites' => $s('favorites', fn() => self::favorites($user_id)),
            'social'    => $s('social',    fn() => self::social($user_id)),
            'donations' => $s('donations', fn() => self::donations($user_id)),
            'training'  => $s('training',  fn() => self::training($user_id)),
            'system'    => $s('system',    fn() => self::system($user_id)),
        ];
    }

    private static function section(string $name, callable $fn)
    {
        try {
            return $fn();
        } catch (\Throwable $e) {
            Log::warning('AdminUserOverviewService: section "' . $name . '" failed: ' . $e->getMessage());
            return null;
        }
    }

    /** count + latest LIMIT rows of a query */
    private static function block($query, string $order_col = 'id'): array
    {
        return [
            'count' => (clone $query)->count(),
            'items' => $query->orderByDesc($order_col)->limit(self::LIMIT)->get(),
        ];
    }

    private static function profile(User $user): array
    {
        return array_merge($user->only([
            'id', 'name', 'surname', 'email', 'phone_number', 'country', 'city', 'my_bio', 'birthday', 'image',
            'lang', 'grade', 'mount_grade', 'animations', 'is_team_member', 'member_status',
            'email_verified_at', 'last_seen_at', 'onboarded_at', 'created_at', 'updated_at',
            'use_offline_data_on_mobile',
        ]), [
            'is_banned' => $user->isBanned(),
        ]);
    }

    private static function account(User $user): array
    {
        $id = $user->id;

        $org = DB::table('partner_organization_members as m')
            ->leftJoin('partner_organizations as o', 'o.id', '=', 'm.organization_id')
            ->where('m.user_id', $id)
            ->select('o.id', 'o.name', 'o.discount', 'm.created_at as joined_at')
            ->first();

        $warehouse = DB::table('warehouse_user as wu')
            ->leftJoin('warehouses as w', 'w.id', '=', 'wu.warehouse_id')
            ->where('wu.user_id', $id)
            ->select('w.id', 'w.name', 'w.is_sale_point')
            ->first();

        $tokens = DB::table('personal_access_tokens')
            ->where('tokenable_type', User::class)
            ->where('tokenable_id', $id);

        $notif = DB::table('user_notifications')->where('user_id', $id)->value('notification_type');
        $notif = is_string($notif) ? json_decode($notif, true) : $notif;

        return [
            'roles'          => $user->role->map->only(['id', 'name', 'slug'])->values(),
            'permissions'    => $user->permissions->map->only(['id', 'subject', 'action'])->values(),
            'organization'   => $org,
            'warehouse'      => $warehouse,
            'social_accounts'=> DB::table('social_accounts')->where('user_id', $id)
                ->select('id', 'provider', 'created_at')->get(),
            'strava'         => DB::table('strava_connections')->where('user_id', $id)
                ->select('athlete_id', 'athlete_name', 'scope', 'expires_at', 'created_at')->first(),
            'sessions'       => self::block(
                (clone $tokens)->select('id', 'name', 'last_used_at', 'created_at'),
                'last_used_at'
            ),
            'notification_preferences' => [
                'enabled'  => is_array($notif) ? count(array_filter($notif)) : 0,
                'total'    => is_array($notif) ? count($notif) : 0,
                'disabled' => is_array($notif) ? array_keys(array_filter($notif, fn($v) => !$v)) : [],
            ],
        ];
    }

    private static function points(User $user): array
    {
        $rows = [];
        foreach (User::pointsCategories() as $alias => [, $slug]) {
            $count = (int) ($user->{$alias} ?? 0);
            $coef  = (int) CoefficientService::get($slug);
            $rows[] = ['key' => $alias, 'count' => $count, 'coefficient' => $coef, 'points' => $count * $coef];
        }

        return ['total' => User::pointsFromCounts($user), 'breakdown' => $rows];
    }

    private static function activity(int $id): array
    {
        return [
            'route_reviews' => self::block(
                DB::table('sport_route_reviews as r')
                    ->leftJoin('routes as rt', 'rt.id', '=', 'r.route_id')
                    ->where('r.user_id', $id)
                    ->select('r.id', 'r.stars', 'r.text', 'r.published', 'r.admin_hidden', 'r.ascent_style',
                        'r.created_at', 'rt.id as route_id', 'rt.name as route_name', 'rt.grade as route_grade'),
                'r.id'
            ),
            'mtp_reviews' => self::block(
                DB::table('mtp_reviews as r')
                    ->leftJoin('mtps as m', 'm.id', '=', 'r.mtp_id')
                    ->where('r.user_id', $id)
                    ->select('r.id', 'r.stars', 'r.text', 'r.published', 'r.admin_hidden', 'r.created_at',
                        'm.id as mtp_id', 'm.name as mtp_name'),
                'r.id'
            ),
            'route_ascents' => self::block(
                DB::table('route_ascents as a')
                    ->leftJoin('routes as rt', 'rt.id', '=', 'a.route_id')
                    ->where('a.user_id', $id)
                    ->select('a.id', 'a.ascent_date', 'a.ascent_style', 'a.attempts', 'a.suggested_grade',
                        'a.comment', 'a.created_at', 'rt.name as route_name', 'rt.grade as route_grade'),
                'a.id'
            ),
            'mtp_ascents' => self::block(
                DB::table('mtp_ascents as a')
                    ->leftJoin('mtps as m', 'm.id', '=', 'a.mtp_id')
                    ->where('a.user_id', $id)
                    ->select('a.id', 'a.ascent_date', 'a.ascent_style', 'a.comment', 'a.created_at', 'm.name as mtp_name'),
                'a.id'
            ),
            'summit_ascents' => self::block(
                DB::table('summit_ascent_users as su')
                    ->join('summit_ascents as a', 'a.id', '=', 'su.ascent_id')
                    ->leftJoin('summits as s', 's.id', '=', 'a.summit_id')
                    ->where('su.user_id', $id)
                    ->select('a.id', 'a.ascent_date', 'a.is_gps_validated', 'a.is_archive', 'a.comment',
                        'a.created_at', 's.title as summit_title', 's.url_title as summit_url_title'),
                'a.id'
            ),
            'comments' => self::block(
                DB::table('article_comment_user as cu')
                    ->join('article_comments as c', 'c.id', '=', 'cu.comment_id')
                    ->leftJoin('articles as ar', 'ar.id', '=', 'c.article_id')
                    ->where('cu.user_id', $id)
                    ->select('c.id', 'c.text', 'c.published', 'c.admin_hidden', 'c.deleted_at', 'c.created_at',
                        'ar.url_title as article_url_title', 'ar.category as article_category'),
                'c.id'
            ),
            'partner_posts' => self::block(
                DB::table('partner_posts as p')
                    ->leftJoin('articles as ar', 'ar.id', '=', 'p.article_id')
                    ->where('p.user_id', $id)
                    ->select('p.id', 'p.place', 'p.climb_date', 'p.end_date', 'p.discipline', 'p.grade_from',
                        'p.grade_to', 'p.admin_hidden', 'p.created_at', 'ar.url_title as article_url_title'),
                'p.id'
            ),
            'condition_reports' => self::block(
                DB::table('area_condition_reports as r')
                    ->leftJoin('articles as ar', 'ar.id', '=', 'r.article_id')
                    ->where('r.user_id', $id)
                    ->select('r.id', 'r.condition', 'r.note', 'r.admin_hidden', 'r.created_at', 'ar.url_title as article_url_title'),
                'r.id'
            ),
            'film_ratings' => self::block(
                DB::table('film_reitings as r')
                    ->leftJoin('films as f', 'f.id', '=', 'r.film_id')
                    ->where('r.user_id', $id)
                    ->select('r.id', 'r.review_stars', 'r.created_at', 'f.url_title as film_url_title'),
                'r.id'
            ),
            'blog_posts' => self::block(
                DB::table('posts')->where('user_id', $id)->select('id', 'url_title', 'published', 'created_at')
            ),
        ];
    }

    private static function shop(int $id): array
    {
        $order_ids = DB::table('orders')->where('user_id', $id)->pluck('id')
            ->merge(DB::table('orders_user')->where('user_id', $id)->pluck('order_id'))
            ->unique()->values();

        $orders = DB::table('orders as o')
            ->whereIn('o.id', $order_ids)
            ->select('o.id', 'o.status', 'o.confirm', 'o.payment', 'o.payment_amount', 'o.discount',
                'o.delivery_price', 'o.is_custom', 'o.tbc_pay_status', 'o.created_at')
            ->selectSub(fn($q) => $q->from('order_products')->whereColumn('order_id', 'o.id')->selectRaw('COALESCE(SUM(quantity),0)'), 'items_count');

        return [
            'orders'        => self::block($orders, 'o.id'),
            'orders_total_amount' => (float) DB::table('orders')->whereIn('id', $order_ids)->sum('payment_amount'),
            'cart'          => [
                'count'    => DB::table('carts')->where('user_id', $id)->count(),
                'quantity' => (int) DB::table('carts')->where('user_id', $id)->sum('quantity'),
            ],
            'addresses'     => self::block(
                DB::table('user_adreses as a')
                    ->leftJoin('shiped_regions as r', 'r.id', '=', 'a.region_id')
                    ->where('a.user_id', $id)
                    ->select('a.id', 'a.name', 'a.city', 'a.strit', 'a.number', 'a.floor', 'a.flat',
                        'a.entrance', 'a.zip_code', 'a.is_default', 'a.created_at', 'r.region'),
                'a.id'
            ),
            'product_feedbacks' => self::block(
                DB::table('user_product_feedbacks as uf')
                    ->join('product_feedbacks as f', 'f.id', '=', 'uf.feedback_id')
                    ->leftJoin('products as p', 'p.id', '=', 'f.product_id')
                    ->where('uf.user_id', $id)
                    ->select('f.id', 'f.stars', 'f.text', 'f.published', 'f.admin_hidden', 'f.deleted_at',
                        'f.created_at', 'p.url_title as product_url_title'),
                'f.id'
            ),
            'stock_notifications' => self::block(
                DB::table('stock_notifications')->where('user_id', $id)
                    ->select('id', 'option_id', 'locale', 'notified_at', 'created_at')
            ),
            'tour_reservations' => self::block(
                DB::table('tour_reservations_users as tu')
                    ->join('tour_reservations as r', 'r.id', '=', 'tu.reservation_id')
                    ->leftJoin('tours as t', 't.id', '=', 'r.tour_id')
                    ->where('tu.user_id', $id)
                    ->select('r.id', 'r.check_in', 'r.persons', 'r.confirm', 'r.verificate', 'r.created_at',
                        't.url_title as tour_url_title'),
                'r.id'
            ),
            // Gear rentals linked to this account (rentals.user_id) — FUNCTIONS_DOCS/shop/gear_rental.md
            'gear_rentals' => self::block(
                DB::table('rentals as gr')
                    ->where('gr.user_id', $id)
                    ->select('gr.id', 'gr.status', 'gr.start_at', 'gr.days', 'gr.due_at', 'gr.returned_at', 'gr.total', 'gr.created_at')
                    ->selectSub(fn($q) => $q->from('rental_items')->whereColumn('rental_id', 'gr.id')->selectRaw("GROUP_CONCAT(CONCAT(quantity, '× ', gear_name) SEPARATOR ', ')"), 'gear'),
                'gr.id'
            ),
            // Products / tours this user is the owner (author) of — user_products / user_tours.
            'owned_products' => self::block(
                DB::table('user_products as up')
                    ->join('products as p', 'p.id', '=', 'up.product_id')
                    ->where('up.user_id', $id)
                    ->select('p.id', 'p.url_title', 'p.published', 'p.created_at'),
                'p.id'
            ),
            'owned_tours' => self::block(
                DB::table('user_tours as ut')
                    ->join('tours as t', 't.id', '=', 'ut.tour_id')
                    ->where('ut.user_id', $id)
                    ->select('t.id', 't.url_title', 't.published', 't.created_at'),
                't.id'
            ),
        ];
    }

    private static function favorites(int $id): array
    {
        return [
            'outdoors' => self::block(
                DB::table('favorite_outdoor_areas as f')
                    ->leftJoin('articles as a', 'a.id', '=', 'f.article_id')
                    ->where('f.user_id', $id)
                    ->select('f.id', 'f.created_at', 'a.url_title', 'a.category'),
                'f.id'
            ),
            'products' => self::block(
                DB::table('favorite_products as f')
                    ->leftJoin('products as p', 'p.id', '=', 'f.product_id')
                    ->where('f.user_id', $id)
                    ->select('f.id', 'f.created_at', 'p.url_title'),
                'f.id'
            ),
            'films' => self::block(
                DB::table('favorite_films as f')
                    ->leftJoin('films as fl', 'fl.id', '=', 'f.film_id')
                    ->where('f.user_id', $id)
                    ->select('f.id', 'f.created_at', 'fl.url_title'),
                'f.id'
            ),
            'events' => self::block(
                DB::table('interested_events as f')
                    ->leftJoin('events as e', 'e.id', '=', 'f.event_id')
                    ->where('f.user_id', $id)
                    ->select('f.id', 'f.created_at', 'e.url_title', 'e.start_data', 'e.end_data'),
                'f.id'
            ),
        ];
    }

    private static function social(int $id): array
    {
        $people = fn(string $join_col, string $where_col) => DB::table('user_follows as uf')
            ->join('users as u', 'u.id', '=', 'uf.' . $join_col)
            ->where('uf.' . $where_col, $id)
            ->select('u.id', 'u.name', 'u.surname', 'u.image', 'uf.created_at');

        return [
            'followers' => self::block($people('follower_id', 'followed_id'), 'uf.id'),
            'following' => self::block($people('followed_id', 'follower_id'), 'uf.id'),
            'sites'     => DB::table('user_sites')->where('user_id', $id)->select('id', 'url')->get(),
        ];
    }

    private static function donations(int $id): array
    {
        $q = DB::table('donations')->where('user_id', $id);

        return array_merge(
            self::block((clone $q)->select('id', 'amount', 'currency', 'status', 'comment', 'created_at')),
            ['totals' => (clone $q)->selectRaw('currency, SUM(amount) as amount, COUNT(*) as n')->groupBy('currency')->get()]
        );
    }

    private static function training(int $id): array
    {
        return [
            'workouts' => self::block(
                DB::table('user_workouts')->where('user_id', $id)->whereNull('deleted_at')
                    ->select('id', 'name', 'type', 'reps', 'sets', 'updated_at'),
                'updated_at'
            ),
            'plans' => self::block(
                DB::table('user_plan_states as s')
                    ->leftJoin('training_plans as p', 'p.id', '=', 's.plan_id')
                    ->where('s.user_id', $id)
                    ->select('s.id', 's.is_active', 's.activated_at', 's.start_date', 'p.name as plan_name', 'p.emoji'),
                's.id'
            ),
            'history' => self::block(
                DB::table('user_training_history')->where('user_id', $id)
                    ->select('id', 'date', 'workout_name', 'workout_type', 'reps_completed', 'sets_completed', 'status'),
                'date'
            ),
            'boards' => self::block(
                DB::table('boards')->where('user_id', $id)->select('id', 'name', 'degree', 'hold_set', 'created_at')
            ),
            'board_routes'  => DB::table('board_routes')->where('user_id', $id)->count(),
            'board_ascents' => DB::table('board_routes_ascent')->where('user_id', $id)->count(),
        ];
    }

    private static function system(int $id): array
    {
        $people = fn($q, $col) => $q->leftJoin('users as u', 'u.id', '=', $col);

        return [
            'complaints_filed' => self::block(
                $people(DB::table('user_complaints as c'), 'c.complainted_user_id')
                    ->where('c.user_id', $id)
                    ->select('c.id', 'c.category', 'c.comment', 'c.decision', 'c.created_at', 'u.id as other_id', 'u.name', 'u.surname'),
                'c.id'
            ),
            'complaints_received' => self::block(
                $people(DB::table('user_complaints as c'), 'c.user_id')
                    ->where('c.complainted_user_id', $id)
                    ->select('c.id', 'c.category', 'c.comment', 'c.decision', 'c.created_at', 'u.id as other_id', 'u.name', 'u.surname'),
                'c.id'
            ),
            'tasks_for' => self::block(
                DB::table('tasks')->where('for_user_id', $id)->select('id', 'title', 'status', 'deadline', 'created_at')
            ),
            'tasks_from' => self::block(
                DB::table('tasks')->where('from_user_id', $id)->select('id', 'title', 'status', 'deadline', 'created_at')
            ),
            'requests' => self::block(
                DB::table('user_requests')->where('user_id', $id)->select('id', 'action', 'created_at')
            ),
            'content_notifications' => self::block(
                DB::table('content_notification_logs')->where('user_id', $id)
                    ->select('id', 'notifiable_type', 'notifiable_id', 'notification_key', 'sent_at'),
                'id'
            ),
        ];
    }
}

<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Users section of the guidebook dashboard (GET set_user/get_user_stats, user › show).
 * Aggregates only — the newest sign-ups list carries id / name / surname / image / dates.
 * Docs: docs/FUNCTIONS_DOCS/guide/guide_dashboard.md#users
 */
class UserStatsService
{
    public static function build(): array
    {
        $now   = Carbon::now();
        $today = $now->copy()->startOfDay();
        $week  = $now->copy()->subDays(7);
        $month = $now->copy()->subDays(30);

        $users = DB::table('users');
        $total = (clone $users)->count();

        $banned_ids = DB::table('user_role as ur')
            ->join('roles as r', 'r.id', '=', 'ur.role_id')
            ->where('r.slug', 'ban')
            ->pluck('ur.user_id')->unique();

        $count = fn ($q) => $q->count();

        return [
            'totals' => [
                'total'        => $total,
                'verified'     => $count((clone $users)->whereNotNull('email_verified_at')),
                'unverified'   => $count((clone $users)->whereNull('email_verified_at')),
                'banned'       => $banned_ids->count(),
                'team_members' => $count((clone $users)->where('is_team_member', 1)),
            ],
            'growth' => [
                'new_today' => $count((clone $users)->where('created_at', '>=', $today)),
                'new_week'  => $count((clone $users)->where('created_at', '>=', $week)),
                'new_month' => $count((clone $users)->where('created_at', '>=', $month)),
            ],
            'activity' => [
                'today' => $count((clone $users)->where('last_seen_at', '>=', $today)),
                'week'  => $count((clone $users)->where('last_seen_at', '>=', $week)),
                'month' => $count((clone $users)->where('last_seen_at', '>=', $month)),
                'never' => $count((clone $users)->whereNull('last_seen_at')),
            ],
            'signups_by_month' => self::signups_by_month($now),
            'profile' => [
                'photo'     => $count((clone $users)->whereNotNull('image')->where('image', '!=', '')),
                'birthday'  => $count((clone $users)->whereNotNull('birthday')),
                'phone'     => $count((clone $users)->whereNotNull('phone_number')->where('phone_number', '!=', '')),
                'location'  => $count((clone $users)->whereNotNull('city')->where('city', '!=', '')->whereNotNull('country')->where('country', '!=', '')),
                'onboarded' => $count((clone $users)->whereNotNull('onboarded_at')),
            ],
            'languages' => [
                'us' => $count((clone $users)->where(fn ($q) => $q->where('lang', 'us')->orWhereNull('lang'))),
                'ka' => $count((clone $users)->where('lang', 'ka')),
            ],
            'ages'    => self::age_groups($now),
            'roles'   => self::roles(),
            'sign_in' => [
                'google'   => DB::table('social_accounts')->where('provider', 'google')->distinct('user_id')->count('user_id'),
                'facebook' => DB::table('social_accounts')->where('provider', 'facebook')->distinct('user_id')->count('user_id'),
            ],
            'follows' => DB::table('user_follows')->count(),   // climber → climber follows
            'recent'  => DB::table('users')
                ->select('id', 'name', 'surname', 'image', 'created_at', 'email_verified_at', 'last_seen_at')
                ->latest('id')->limit(8)->get()
                ->map(fn ($u) => [
                    'id'         => $u->id,
                    'name'       => trim(($u->name ?? '') . ' ' . ($u->surname ?? '')),
                    'image'      => $u->image,
                    'created_at' => $u->created_at,
                    'verified'   => (bool) $u->email_verified_at,
                    'last_seen_at' => $u->last_seen_at,
                ])->values(),
        ];
    }

    /** Last 12 months, oldest first: [{month: '2026-01', count}] (months without sign-ups = 0). */
    private static function signups_by_month(Carbon $now): array
    {
        $from = $now->copy()->startOfMonth()->subMonths(11);
        $rows = DB::table('users')
            ->where('created_at', '>=', $from)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as m, COUNT(*) as c")
            ->groupBy('m')->pluck('c', 'm');

        $out = [];
        for ($i = 0; $i < 12; $i++) {
            $key = $from->copy()->addMonths($i)->format('Y-m');
            $out[] = ['month' => $key, 'count' => (int) ($rows[$key] ?? 0)];
        }
        return $out;
    }

    private static function age_groups(Carbon $now): array
    {
        $groups = ['under_18' => 0, '18_24' => 0, '25_34' => 0, '35_44' => 0, '45_plus' => 0];
        DB::table('users')->whereNotNull('birthday')->pluck('birthday')->each(function ($b) use (&$groups, $now) {
            try { $age = Carbon::parse($b)->diffInYears($now); } catch (\Throwable $e) { return; }
            $key = $age < 18 ? 'under_18' : ($age < 25 ? '18_24' : ($age < 35 ? '25_34' : ($age < 45 ? '35_44' : '45_plus')));
            $groups[$key]++;
        });
        return $groups;
    }

    /** Roles with their user counts, biggest first (roles without users left out). */
    private static function roles(): array
    {
        return DB::table('roles as r')
            ->join('user_role as ur', 'ur.role_id', '=', 'r.id')
            ->select('r.slug', 'r.name', DB::raw('COUNT(DISTINCT ur.user_id) as c'))
            ->groupBy('r.id', 'r.slug', 'r.name')
            ->orderByDesc('c')
            ->get()
            ->map(fn ($r) => ['slug' => $r->slug, 'name' => $r->name, 'count' => (int) $r->c])
            ->all();
    }
}

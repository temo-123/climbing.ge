<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

use App\Models\User;
use App\Models\User\User_tour;
use App\Models\Shop\Tour;
use App\Models\Shop\Tour_reservation;
use App\Models\Shop\Tour_reservation_guide_change;

use App\Notifications\tour\ReservationGuideAssignedNotification;

/**
 * Tour guides — docs/FUNCTIONS_DOCS/shop/tour_guides.md
 *
 * - Every tour has up to 4 guides (user_tours); one of them is the GENERAL guide
 *   (is_main). The first guide added becomes general automatically.
 * - Every reservation has a guide in charge (tour_reservations.guide_id), always one of
 *   the tour's guides. New reservations go to the general guide.
 * - Switching (assign) is logged in tour_reservation_guide_changes and mailed to exactly
 *   two addresses: the new guide and info@ (send_switch_mails).
 */
class TourGuideService
{
    const MAX_GUIDES = 4;
    // climbing.ge fee when a reservation is with a guide who is NOT the tour's general guide
    const COMMISSION_PERCENT = 15;
    const PUBLIC_USER = ['users.id', 'users.name', 'users.surname', 'users.image'];

    /** Guide relations of a tour, general first, then in the order they were added. */
    public static function relations($tour_id)
    {
        $rels = User_tour::where('tour_id', $tour_id)->orderByDesc('is_main')->orderBy('id')->get();
        // self-heal: guides but nobody general (old data / manual edits) → the first one is
        if ($rels->isNotEmpty() && !$rels->first()->is_main) {
            $rels->first()->is_main = true;
            $rels->first()->save();
        }
        return $rels;
    }

    /** The general guide's user id (falls back to the first guide). */
    public static function main_guide_id($tour_id): ?int
    {
        $rel = self::relations($tour_id)->first();
        return $rel ? (int) $rel->user_id : null;
    }

    public static function is_guide($tour_id, $user_id): bool
    {
        return User_tour::where('tour_id', $tour_id)->where('user_id', $user_id)->exists();
    }

    /** Admin list: guides with contact + how many open reservations each has in charge. */
    public static function admin_guides(Tour $tour): array
    {
        $today = now()->toDateString();
        $open = Tour_reservation::where('tour_id', $tour->id)
            ->where(fn ($q) => $q->whereNull('check_in')->orWhere('check_in', '>=', $today))
            ->selectRaw('guide_id, COUNT(*) as n')->groupBy('guide_id')->pluck('n', 'guide_id');
        $total = Tour_reservation::where('tour_id', $tour->id)->selectRaw('guide_id, COUNT(*) as n')->groupBy('guide_id')->pluck('n', 'guide_id');

        return self::relations($tour->id)->map(function ($rel) use ($open, $total) {
            $u = User::find($rel->user_id, ['id', 'name', 'surname', 'image', 'email', 'phone_number']);
            if (!$u) return null;
            return [
                'relation_id' => $rel->id,
                'is_main' => (bool) $rel->is_main,
                'user' => $u->only(['id', 'name', 'surname', 'image', 'email', 'phone_number']),
                'open_reservations' => (int) ($open[$u->id] ?? 0),
                'all_reservations' => (int) ($total[$u->id] ?? 0),
            ];
        })->filter()->values()->all();
    }

    /**
     * Users who may be tour guides: `tour_guide › show` directly or via a role (Guide, Admin),
     * banned users excluded. Used for the "Add a guide" list and checked in add_guide().
     */
    public static function candidate_ids()
    {
        $perm = DB::table('permissions')->where('subject', 'tour_guide')->where('action', 'show')->value('id');
        if (!$perm) return collect();
        $via_role = DB::table('user_role')->join('role_permissions', 'role_permissions.role_id', '=', 'user_role.role_id')
            ->where('role_permissions.permission_id', $perm)->pluck('user_role.user_id');
        $direct = DB::table('user_permissions')->where('permission_id', $perm)->pluck('user_id');
        $banned = DB::table('user_role')->join('roles', 'roles.id', '=', 'user_role.role_id')->where('roles.slug', 'ban')->pluck('user_role.user_id');
        return $via_role->merge($direct)->unique()->diff($banned)->values();
    }

    public static function candidates(): array
    {
        return User::whereIn('id', self::candidate_ids())->orderBy('name')->get(['id', 'name', 'surname', 'image', 'email'])
            ->map(fn ($u) => $u->only(['id', 'name', 'surname', 'image', 'email']))->all();
    }

    /** @return string|null error */
    public static function add_guide(Tour $tour, $user_id, bool $check_permission = true): ?string
    {
        if (!User::where('id', $user_id)->exists()) return 'User not found.';
        if ($check_permission && !self::candidate_ids()->contains((int) $user_id)) return 'Only users with the Guide role (tour_guide permission) can be tour guides.';
        if (self::is_guide($tour->id, $user_id)) return 'This user is already a guide for this tour.';
        if (User_tour::where('tour_id', $tour->id)->count() >= self::MAX_GUIDES) return 'Maximum ' . self::MAX_GUIDES . ' guides allowed per tour.';

        $first = !User_tour::where('tour_id', $tour->id)->exists();
        User_tour::create(['tour_id' => $tour->id, 'user_id' => $user_id, 'is_main' => $first]);

        // the tour had no guide → its reservations had nobody in charge
        if ($first) {
            Tour_reservation::where('tour_id', $tour->id)->whereNull('guide_id')->update(['guide_id' => $user_id]);
        }
        return null;
    }

    /** Make a guide the tour's general guide. */
    public static function set_main(Tour $tour, $user_id): ?string
    {
        if (!self::is_guide($tour->id, $user_id)) return 'This user is not a guide of this tour.';
        DB::transaction(function () use ($tour, $user_id) {
            User_tour::where('tour_id', $tour->id)->update(['is_main' => false]);
            User_tour::where('tour_id', $tour->id)->where('user_id', $user_id)->update(['is_main' => true]);
        });
        return null;
    }

    /**
     * Remove a guide. A general guide hands over to the next guide; the removed guide's
     * open reservations move to the (new) general guide, with the usual mails.
     * @return array ['error' => ?string, 'moved' => int]
     */
    public static function remove_guide(Tour $tour, $user_id, ?User $by = null): array
    {
        $rel = User_tour::where('tour_id', $tour->id)->where('user_id', $user_id)->first();
        if (!$rel) return ['error' => 'This user is not a guide of this tour.', 'moved' => 0];

        $was_main = (bool) $rel->is_main;
        $rel->delete();

        if ($was_main) {
            $next = User_tour::where('tour_id', $tour->id)->orderBy('id')->first();
            if ($next) { $next->is_main = true; $next->save(); }
        }

        $new_id = self::main_guide_id($tour->id);
        $moved = 0;
        foreach (self::open_reservations_of($tour->id, $user_id) as $r) {
            if ($new_id) {
                self::assign($r, $new_id, $by, 'guide_removed', null, true);
            } else {
                $r->guide_id = null; $r->save(); // no guide left
            }
            $moved++;
        }
        // finished / past ones keep the history but point to nobody who is still a guide
        Tour_reservation::where('tour_id', $tour->id)->where('guide_id', $user_id)->update(['guide_id' => $new_id]);

        return ['error' => null, 'moved' => $moved];
    }

    /**
     * Guide page (/guide_page): my tours (general or helper), reservations in my charge
     * (upcoming + last 60 days), fees I owe, numbers for the cards.
     */
    public static function guide_panel(User $user): array
    {
        $rels = User_tour::where('user_id', $user->id)->get()->keyBy('tour_id');
        $tours = Tour::with('us_tour:id,title', 'ka_tour:id,title')->whereIn('id', $rels->keys())->get()->map(fn ($t) => [
            'id' => $t->id,
            'url_title' => $t->url_title,
            'us_title' => optional($t->us_tour)->title ?: $t->url_title,
            'ka_title' => optional($t->ka_tour)->title ?: (optional($t->us_tour)->title ?: $t->url_title),
            'published' => (int) $t->published,
            'is_main' => (bool) $rels[$t->id]->is_main,
            'public_url' => rtrim(env('APP_SSH') . env('SHOP_URL'), '/') . '/tour/' . $t->url_title,
        ])->values();

        $titles = $tours->mapWithKeys(fn ($t) => [$t['id'] => ['us' => $t['us_title'], 'ka' => $t['ka_title']]]);
        $since = now()->subDays(60)->toDateString();
        // only reservations with a date (old rows from before the date field are left out)
        $rows = Tour_reservation::where('guide_id', $user->id)
            ->whereNotNull('check_in')->where('check_in', '>=', $since)
            ->orderBy('check_in')->get();
        $today = now()->toDateString();
        $reservations = $rows->map(fn ($r) => [
            'id' => $r->id, 'tour_id' => $r->tour_id, 'tour' => $titles[$r->tour_id] ?? null,
            'name' => $r->name, 'email' => $r->email, 'phone' => $r->phone,
            'check_in' => $r->check_in, 'days' => $r->days, 'persons' => $r->persons, 'tour_language' => $r->tour_language,
            'price_total' => $r->price_total, 'price_currency' => $r->price_currency,
            'verified' => (int) $r->verificate === 1, 'completed' => (bool) $r->completed_at,
            'commission_amount' => $r->commission_amount, 'commission_percent' => $r->commission_percent, 'commission_paid' => (bool) $r->commission_paid_at,
            'upcoming' => $r->check_in >= $today,
            'text' => $r->text,
        ])->values();

        $owed = Tour_reservation::where('guide_id', $user->id)->whereNotNull('commission_percent')->whereNull('commission_paid_at')->get(['commission_amount', 'price_currency']);
        return [
            'tours' => $tours,
            'reservations' => $reservations,
            'stats' => [
                'tours' => $tours->count(),
                'general_of' => $tours->where('is_main', true)->count(),
                'upcoming' => $reservations->where('upcoming', true)->count(),
                'upcoming_confirmed' => $reservations->where('upcoming', true)->where('verified', true)->count(),
                'fees_owed_count' => $owed->count(),
                'fees_owed' => $owed->groupBy(fn ($r) => $r->price_currency ?: 'EUR')->map(fn ($g) => round($g->sum('commission_amount'), 2)),
            ],
        ];
    }

    /** A user is being deleted: leave every tour they guide (see User::booted). */
    public static function release_user(User $user): void
    {
        $tour_ids = User_tour::where('user_id', $user->id)->pluck('tour_id');
        foreach (Tour::whereIn('id', $tour_ids)->get() as $tour) {
            try {
                self::remove_guide($tour, $user->id, auth('sanctum')->user() ?: null);
            } catch (\Throwable $e) {
                Log::warning('Tour guide release failed', ['tour_id' => $tour->id, 'user_id' => $user->id, 'error' => $e->getMessage()]);
            }
        }
    }

    /**
     * Hand the tour over: $user_id becomes the general guide, and (optionally) every open
     * reservation of the previous general guide moves to them.
     */
    public static function handover(Tour $tour, $user_id, bool $move_reservations, bool $notify_clients, ?User $by = null): array
    {
        if (!self::is_guide($tour->id, $user_id)) return ['error' => 'This user is not a guide of this tour.', 'moved' => 0];
        $old = self::main_guide_id($tour->id);
        self::set_main($tour, $user_id);

        $moved = 0;
        if ($move_reservations && $old && (int) $old !== (int) $user_id) {
            foreach (self::open_reservations_of($tour->id, $old) as $r) {
                self::assign($r, $user_id, $by, 'handover', null, $notify_clients);
                $moved++;
            }
        }
        return ['error' => null, 'moved' => $moved];
    }

    /** Reservations of a tour that are still ahead (or without a date) and in $user_id's charge. */
    public static function open_reservations_of($tour_id, $user_id)
    {
        return Tour_reservation::where('tour_id', $tour_id)->where('guide_id', $user_id)
            ->where(fn ($q) => $q->whereNull('check_in')->orWhere('check_in', '>=', now()->toDateString()))
            ->get();
    }

    /**
     * Switch the guide in charge of one reservation (must be a guide of its tour).
     * @return string|null error
     */
    public static function assign(Tour_reservation $r, $user_id, ?User $by = null, string $reason = 'manual', ?string $note = null, bool $notify_client = true): ?string
    {
        if (!self::is_guide($r->tour_id, $user_id)) return 'This user is not a guide of this tour.';
        $from = $r->guide_id ? (int) $r->guide_id : null;
        if ($from === (int) $user_id) return null; // nothing to do

        $r->guide_id = (int) $user_id;
        self::apply_commission($r);
        $r->save();

        Tour_reservation_guide_change::create([
            'reservation_id' => $r->id,
            'from_user_id' => $from,
            'to_user_id' => (int) $user_id,
            'changed_by' => $by ? $by->id : null,
            'reason' => $reason,
            'note' => $note ? mb_substr($note, 0, 500) : null,
        ]);

        self::send_switch_mails($r->fresh(), $from, (int) $user_id, $by, $note, $notify_client);
        return null;
    }

    /**
     * Fee snapshot: 15 % of the total when the guide in charge is not the general guide,
     * cleared when the reservation is (back) with the general guide. A paid fee is never touched.
     * Called on every switch (manual / handover / guide removed).
     */
    public static function apply_commission(Tour_reservation $r): void
    {
        if ($r->commission_paid_at) return;
        $main = self::main_guide_id($r->tour_id);
        if ($r->guide_id && $main && (int) $r->guide_id !== (int) $main) {
            $r->commission_percent = self::COMMISSION_PERCENT;
            $r->commission_amount = $r->price_total !== null ? round((float) $r->price_total * self::COMMISSION_PERCENT / 100, 2) : null;
        } else {
            $r->commission_percent = null;
            $r->commission_amount = null;
        }
    }

    /** Admin: mark the tour completed / the fee paid (or undo). @return string|null error */
    public static function set_status(Tour_reservation $r, ?bool $completed, ?bool $paid): ?string
    {
        if ($paid !== null && !$r->commission_percent) return 'This reservation has no fee.';
        if ($completed !== null) $r->completed_at = $completed ? ($r->completed_at ?: now()) : null;
        if ($paid !== null) $r->commission_paid_at = $paid ? ($r->commission_paid_at ?: now()) : null;
        $r->save();
        return null;
    }

    /** History for the details modal, newest first. */
    public static function history(Tour_reservation $r): array
    {
        $rows = Tour_reservation_guide_change::where('reservation_id', $r->id)->orderByDesc('id')->get();
        $ids = $rows->pluck('from_user_id')->merge($rows->pluck('to_user_id'))->merge($rows->pluck('changed_by'))->filter()->unique();
        $names = User::whereIn('id', $ids)->get(['id', 'name', 'surname'])->mapWithKeys(fn ($u) => [$u->id => trim($u->name . ' ' . $u->surname)]);
        return $rows->map(fn ($c) => [
            'id' => $c->id,
            'from' => $c->from_user_id ? ($names[$c->from_user_id] ?? '#' . $c->from_user_id) : null,
            'to' => $c->to_user_id ? ($names[$c->to_user_id] ?? '#' . $c->to_user_id) : null,
            'by' => $c->changed_by ? ($names[$c->changed_by] ?? '#' . $c->changed_by) : null,
            'reason' => $c->reason,
            'note' => $c->note,
            'at' => $c->created_at,
        ])->all();
    }

    /** Contact of a guide for mails / modal ({name, email, phone}). */
    public static function contact($user_id): ?array
    {
        if (!$user_id) return null;
        $u = User::find($user_id, ['id', 'name', 'surname', 'email', 'phone_number']);
        return $u ? ['id' => $u->id, 'name' => trim($u->name . ' ' . $u->surname), 'email' => $u->email, 'phone' => $u->phone_number] : null;
    }

    /**
     * A switch sends exactly two mails: the new guide ("a reservation is now yours", with
     * their own reservations page) and info@ (sites.email, same content, office wording,
     * admin dashboard link). Nobody else is mailed.
     */
    private static function send_switch_mails(Tour_reservation $r, ?int $from, int $to, ?User $by, ?string $note, bool $notify_client): void
    {
        $data = TourReservationService::mail_data($r);
        $new = self::contact($to);
        $old = self::contact($from);
        $by_name = $by ? trim($by->name . ' ' . $by->surname) : null;

        $send = function (callable $fn, string $kind) use ($r) {
            try { $fn(); } catch (\Throwable $e) {
                Log::warning('Tour guide switch mail failed', ['kind' => $kind, 'reservation_id' => $r->id, 'error' => $e->getMessage()]);
            }
        };

        if ($new && $new['email']) {
            $send(fn () => Notification::route('mail', $new['email'])->notify(new ReservationGuideAssignedNotification($data, $old, $by_name, $note, TourReservationService::guide_url())), 'assigned');
        }
        $site = \App\Models\Site::first();
        $office = ($site && $site->email) ? $site->email : config('mail.from.address');
        if ($office && strtolower($office) !== strtolower($new['email'] ?? '')) {
            $send(fn () => Notification::route('mail', $office)->notify(new ReservationGuideAssignedNotification($data, $old, $by_name, $note, TourReservationService::admin_url(), true)), 'office');
        }
    }
}

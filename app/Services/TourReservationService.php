<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

use App\Models\Site;
use App\Models\User;
use App\Models\Shop\Tour;
use App\Models\Shop\Tour_reservation;
use App\Models\Shop\Tour_reservations_user;

use App\Notifications\tour\AdminNewReservationNotification;
use App\Notifications\tour\ReservationCreatingGuideNotification;
use App\Notifications\tour\ReservationSacsesfulNotification;
use App\Notifications\tour\ReservationVerifyEmail;

/**
 * Tour reservation flow (docs/FUNCTIONS_DOCS/shop/tour_reservation_verification.md):
 *
 *   create()  → row with verificate = 0, "confirm your reservation" mail to the
 *               reserver (signed link), "new reservation" mail to the site address
 *   verify()  → verificate = 1 + verified_at / verify_method, linked to the account
 *               with that email, "thank you" mail to the reserver + mail to the guide
 *
 * The link token is an HMAC of id + email (like order / comment confirmation),
 * so nothing secret is stored and a changed email kills old links.
 */
class TourReservationService
{
    // tour languages the guest can choose (form, mails, dashboard)
    const LANGUAGES = ['ka' => 'Georgian', 'ru' => 'Russian', 'en' => 'English'];

    public static function language_label($code): ?string
    {
        return $code ? (self::LANGUAGES[$code] ?? $code) : null;
    }

    public static function token(Tour_reservation $reservation): string
    {
        return hash_hmac('sha256', 'tour_reservation|' . $reservation->id . '|' . strtolower(trim((string) $reservation->email)), config('app.key'));
    }

    public static function token_valid(Tour_reservation $reservation, $token): bool
    {
        return is_string($token) && $token !== '' && hash_equals(self::token($reservation), $token);
    }

    public static function is_verified(Tour_reservation $reservation): bool
    {
        return (int) $reservation->verificate === 1;
    }

    /**
     * @param array      $data            validated form_data
     * @param User|null  $user            logged-in reserver (linked right away)
     * @param bool       $admin_verified  created in the admin panel → verified at once, no confirm mail
     */
    public static function create(array $data, $tour_id, ?User $user = null, bool $admin_verified = false): Tour_reservation
    {
        // price at this moment, recalculated on the server (TourPriceService)
        $tour = Tour::find($tour_id);
        $days = isset($data['days']) ? (int) $data['days'] : null;
        $persons = isset($data['persons']) ? (int) $data['persons'] : null;
        $quote = ($tour && $days && $persons) ? TourPriceService::quote($tour, $days, $persons) : null;

        $reservation = new Tour_reservation();
        $reservation->fill([
            'check_in' => $data['check_in'] ?? null,
            'persons' => $persons,
            'days' => $days,
            'tour_language' => in_array($data['tour_language'] ?? null, array_keys(self::LANGUAGES), true) ? $data['tour_language'] : null,
            'price_total' => $quote ? $quote['total'] : null,
            'price_currency' => $quote ? $quote['currency'] : ($tour ? TourPriceService::currency($tour) : null),
            'price_details' => $quote,
            'name' => $data['name'] ?? null,
            'email' => isset($data['email']) ? trim($data['email']) : null,
            'phone' => $data['phone'] ?? null,
            'country' => $data['country'] ?? null,
            'city' => $data['city'] ?? null,
            'text' => $data['text'] ?? null,
            'tour_id' => $tour_id,
            // the tour's general guide is in charge (TourGuideService)
            'guide_id' => TourGuideService::main_guide_id($tour_id),
            'verificate' => 0,
        ]);
        $reservation->save();

        if ($user) {
            self::link_user($reservation, $user->id);
        }

        if ($admin_verified) {
            self::verify($reservation, 'admin');
        } else {
            self::send_verify_mail($reservation);
        }

        self::notify_admin($reservation);

        return $reservation;
    }

    /**
     * Mark as verified and send the "reservation complete" mails.
     * Returns false when it already was verified (nothing is sent twice).
     */
    public static function verify(Tour_reservation $reservation, string $method = 'email'): bool
    {
        if (self::is_verified($reservation)) {
            return false;
        }

        $reservation->verificate = 1;
        $reservation->verified_at = now();
        $reservation->verify_method = $method;
        $reservation->save();

        // a guest who already has an account with this email sees it in the panel
        if (!Tour_reservations_user::where('reservation_id', $reservation->id)->exists() && $reservation->email) {
            $account = User::where('email', $reservation->email)->first();
            if ($account) {
                self::link_user($reservation, $account->id);
            }
        }

        self::send_completed_mails($reservation);

        return true;
    }

    public static function link_user(Tour_reservation $reservation, $user_id): void
    {
        Tour_reservations_user::firstOrCreate([
            'reservation_id' => $reservation->id,
            'user_id' => $user_id,
        ]);
    }

    /**
     * Everything the admin details modal shows: the row, the tour (titles, location,
     * duration, price, public link), its guides, linked accounts and how many other
     * reservations came from the same email.
     */
    public static function admin_details(Tour_reservation $reservation): array
    {
        $tour = Tour::with('us_tour:id,title,location,duration', 'ka_tour:id,title,location,duration')->find($reservation->tour_id);
        $guides = $tour ? $tour->user()->get(['users.id', 'users.name', 'users.surname', 'users.email', 'users.phone_number']) : collect();
        $accounts = $reservation->users()->get(['users.id', 'users.name', 'users.surname', 'users.email', 'users.image']);

        $data = $reservation->toArray();
        $data['tour'] = $tour ? [
            'id' => $tour->id,
            'url_title' => $tour->url_title,
            'published' => (int) $tour->published === 1,
            'min_price' => $tour->min_price,
            'us_title' => optional($tour->us_tour)->title,
            'ka_title' => optional($tour->ka_tour)->title,
            'us_location' => optional($tour->us_tour)->location,
            'ka_location' => optional($tour->ka_tour)->location,
            'us_duration' => optional($tour->us_tour)->duration,
            'ka_duration' => optional($tour->ka_tour)->duration,
            'public_url' => rtrim(env('APP_SSH') . env('SHOP_URL'), '/') . '/tour/' . $tour->url_title,
        ] : null;
        $data['guides'] = $guides->map(fn ($g) => $g->only(['id', 'name', 'surname', 'email', 'phone_number']))->values();
        $data['accounts'] = $accounts->map(fn ($u) => $u->only(['id', 'name', 'surname', 'email', 'image']))->values();
        $data['guide'] = TourGuideService::contact($reservation->guide_id);
        $data['tour_guides'] = TourGuideService::relations($reservation->tour_id)->map(function ($rel) {
            $c = TourGuideService::contact($rel->user_id);
            return $c ? $c + ['is_main' => (bool) $rel->is_main] : null;
        })->filter()->values();
        $data['guide_history'] = TourGuideService::history($reservation);
        $data['same_email_count'] = $reservation->email
            ? Tour_reservation::where('email', $reservation->email)->where('id', '!=', $reservation->id)->count()
            : 0;

        return $data;
    }

    /**
     * Reservations dashboard (admin): numbers for the stat cards, reservations per month
     * (6 back … 6 ahead, by check-in), top tours, and a light list for the calendar.
     * "Upcoming" = check-in today or later. Money only counts confirmed reservations.
     */
    public static function dashboard_stats(): array
    {
        $rows = Tour_reservation::get(['id', 'tour_id', 'guide_id', 'name', 'check_in', 'days', 'persons', 'tour_language', 'price_total', 'price_currency', 'verificate', 'created_at', 'completed_at', 'commission_percent', 'commission_amount', 'commission_paid_at']);
        $fees = ['unpaid_count' => 0, 'unpaid' => [], 'paid' => [], 'to_complete' => 0];
        $guide_names = User::whereIn('id', $rows->pluck('guide_id')->filter()->unique())->get(['id', 'name', 'surname'])
            ->mapWithKeys(fn ($u) => [$u->id => trim($u->name . ' ' . $u->surname)]);
        $by_guide = [];
        $today = Carbon::today();

        $tours = Tour::with('us_tour:id,title', 'ka_tour:id,title')->get(['id', 'url_title', 'us_tour_id', 'ka_tour_id'])
            ->mapWithKeys(fn ($t) => [$t->id => [
                'us' => optional($t->us_tour)->title ?: $t->url_title,
                'ka' => optional($t->ka_tour)->title ?: (optional($t->us_tour)->title ?: $t->url_title),
            ]]);

        $date_of = function ($r) {
            try { return $r->check_in ? Carbon::parse($r->check_in)->startOfDay() : null; } catch (\Throwable $e) { return null; }
        };

        $totals = ['all' => 0, 'waiting' => 0, 'confirmed' => 0, 'upcoming' => 0, 'upcoming_confirmed' => 0, 'past' => 0, 'persons_upcoming' => 0, 'this_month' => 0, 'new_7_days' => 0];
        $money = []; // currency => ['upcoming' => x, 'this_year' => y]
        $by_tour = [];
        $months = [];
        for ($i = -6; $i <= 6; $i++) {
            $m = $today->copy()->startOfMonth()->addMonths($i);
            $months[$m->format('Y-m')] = ['month' => $m->format('Y-m'), 'confirmed' => 0, 'waiting' => 0];
        }
        $calendar = [];

        foreach ($rows as $r) {
            $confirmed = (int) $r->verificate === 1;
            $d = $date_of($r);
            if ($r->commission_percent) {
                $cur = $r->price_currency ?: TourPriceService::DEFAULT_CURRENCY;
                $key = $r->commission_paid_at ? 'paid' : 'unpaid';
                $fees[$key][$cur] = ($fees[$key][$cur] ?? 0) + (float) $r->commission_amount;
                if (!$r->commission_paid_at) $fees['unpaid_count']++;
            }
            // confirmed trips that already ended but nobody marked completed
            if ($confirmed && !$r->completed_at && $d && $d->copy()->addDays(max((int) $r->days, 1) - 1)->lt($today)) $fees['to_complete']++;
            $totals['all']++;
            $confirmed ? $totals['confirmed']++ : $totals['waiting']++;
            if ($r->created_at && $r->created_at->gte($today->copy()->subDays(7))) $totals['new_7_days']++;

            if ($d) {
                $end = $d->copy()->addDays(max((int) $r->days, 1) - 1);
                if ($end->gte($today)) {
                    $totals['upcoming']++;
                    $totals['persons_upcoming'] += (int) $r->persons;
                    if ($confirmed) $totals['upcoming_confirmed']++;
                    $gk = $r->guide_id ?: 0;
                    $by_guide[$gk] = $by_guide[$gk] ?? ['guide_id' => $r->guide_id, 'name' => $r->guide_id ? ($guide_names[$r->guide_id] ?? '#' . $r->guide_id) : null, 'upcoming' => 0, 'confirmed' => 0, 'persons' => 0];
                    $by_guide[$gk]['upcoming']++;
                    if ($confirmed) $by_guide[$gk]['confirmed']++;
                    $by_guide[$gk]['persons'] += (int) $r->persons;
                } else {
                    $totals['past']++;
                }
                if ($d->isSameMonth($today)) $totals['this_month']++;
                $key = $d->format('Y-m');
                if (isset($months[$key])) $months[$key][$confirmed ? 'confirmed' : 'waiting']++;

                if ($confirmed && $r->price_total !== null) {
                    $cur = $r->price_currency ?: TourPriceService::DEFAULT_CURRENCY;
                    $money[$cur] = $money[$cur] ?? ['upcoming' => 0, 'this_year' => 0];
                    if ($end->gte($today)) $money[$cur]['upcoming'] += (float) $r->price_total;
                    if ($d->year === $today->year) $money[$cur]['this_year'] += (float) $r->price_total;
                }

                // calendar: last 3 months … everything ahead
                if ($end->gte($today->copy()->subMonths(3)->startOfMonth())) {
                    $calendar[] = [
                        'id' => $r->id,
                        'tour_id' => $r->tour_id,
                        'name' => $r->name,
                        'start' => $d->format('Y-m-d'),
                        'days' => max((int) $r->days, 1),
                        'persons' => (int) $r->persons,
                        'confirmed' => $confirmed,
                        'guide' => $r->guide_id ? ($guide_names[$r->guide_id] ?? null) : null,
                        'language' => $r->tour_language,
                        'completed' => (bool) $r->completed_at,
                    ];
                }
            }

            $by_tour[$r->tour_id] = $by_tour[$r->tour_id] ?? ['tour_id' => $r->tour_id, 'count' => 0, 'confirmed' => 0, 'persons' => 0];
            $by_tour[$r->tour_id]['count']++;
            if ($confirmed) $by_tour[$r->tour_id]['confirmed']++;
            $by_tour[$r->tour_id]['persons'] += (int) $r->persons;
        }

        usort($by_tour, fn ($a, $b) => $b['count'] <=> $a['count']);
        $by_guide = array_values($by_guide);
        usort($by_guide, fn ($a, $b) => $b['upcoming'] <=> $a['upcoming']);

        return [
            'totals' => $totals,
            'confirm_rate' => $totals['all'] ? round($totals['confirmed'] / $totals['all'] * 100) : 0,
            'money' => $money,
            'months' => array_values($months),
            'top_tours' => array_slice($by_tour, 0, 5),
            'by_guide' => $by_guide,
            'fees' => $fees,
            'tours' => $tours,
            'calendar' => $calendar,
        ];
    }

    /** Safe summary for the public confirmation page (no phone / address). */
    public static function public_summary(Tour_reservation $reservation): array
    {
        $tour = Tour::with('us_tour:id,title', 'ka_tour:id,title')->find($reservation->tour_id);

        return [
            'id' => $reservation->id,
            'verified' => self::is_verified($reservation),
            'verified_at' => $reservation->verified_at,
            'check_in' => $reservation->check_in,
            'persons' => $reservation->persons,
            'days' => $reservation->days,
            'tour_language' => $reservation->tour_language,
            'price_total' => $reservation->price_total,
            'price_currency' => $reservation->price_currency,
            'price_details' => $reservation->price_details,
            'name' => $reservation->name,
            'email' => $reservation->email,
            'tour' => $tour ? [
                'url_title' => $tour->url_title,
                'us_title' => optional($tour->us_tour)->title,
                'ka_title' => optional($tour->ka_tour)->title,
            ] : null,
        ];
    }

    public static function confirm_url(Tour_reservation $reservation): string
    {
        return rtrim(env('APP_SSH') . env('SHOP_URL'), '/') . '/confirm_reservation/' . $reservation->id . '/' . self::token($reservation);
    }

    public static function admin_url(): string
    {
        return rtrim(env('APP_SSH') . env('USER_PAGE_URL'), '/') . '/all_tours_reservations_list';
    }

    // for guides: their own page (works without admin permissions)
    public static function guide_url(): string
    {
        return rtrim(env('APP_SSH') . env('USER_PAGE_URL'), '/') . '/user_tours_reservations_list';
    }

    /** Mail data for the templates: the row + the tour name. */
    public static function mail_data(Tour_reservation $reservation): array
    {
        $data = $reservation->only(['id', 'name', 'email', 'phone', 'country', 'city', 'check_in', 'persons', 'days', 'text']);
        $data['language'] = self::language_label($reservation->tour_language);
        // climbing.ge fee (only staff mails show it — TourGuideService::commission)
        $data['commission'] = $reservation->commission_percent ? [
            'percent' => rtrim(rtrim(number_format((float) $reservation->commission_percent, 2, '.', ''), '0'), '.'),
            'amount' => $reservation->commission_amount !== null ? TourPriceService::format($reservation->commission_amount, $reservation->price_currency) : null,
            'paid' => (bool) $reservation->commission_paid_at,
        ] : null;
        $tour = Tour::with('us_tour:id,title,short_description,location,duration')->find($reservation->tour_id);
        $data['tour'] = $tour ? (optional($tour->us_tour)->title ?: $tour->url_title) : null;
        $data['verified'] = self::is_verified($reservation);
        $data['price'] = self::price_lines($reservation);

        // full tour info (the "reservation confirmed" mails)
        $data['tour_info'] = $tour ? [
            'title' => $data['tour'],
            'location' => trim(strip_tags((string) optional($tour->us_tour)->location)),
            'duration' => trim(strip_tags((string) optional($tour->us_tour)->duration)),
            'short_description' => trim(strip_tags((string) optional($tour->us_tour)->short_description)),
            'url' => rtrim(env('APP_SSH') . env('SHOP_URL'), '/') . '/tour/' . $tour->url_title,
        ] : null;
        // the guide in charge of this reservation (switchable, see TourGuideService)
        $data['guide'] = TourGuideService::contact($reservation->guide_id);
        $data['guides'] = $tour
            ? $tour->user()->get(['users.id', 'users.name', 'users.surname', 'users.email', 'users.phone_number'])
                ->map(fn ($g) => [
                    'name' => trim($g->name . ' ' . $g->surname),
                    'email' => $g->email,
                    'phone' => $g->phone_number,
                ])->values()->all()
            : [];

        return $data;
    }

    /**
     * Price for the mails: ['total' => '480 ₾', 'breakdown' => '120 ₾ × 4 persons, 2 days']
     * or null when no price (on request).
     */
    public static function price_lines(Tour_reservation $reservation): ?array
    {
        $q = $reservation->price_details;
        if ($reservation->price_total === null || !is_array($q)) return null;

        $cur = $reservation->price_currency;
        $days = (int) ($q['days'] ?? $reservation->days);
        $persons = (int) ($q['persons'] ?? $reservation->persons);
        $days_text = $days . ' ' . ($days === 1 ? 'day' : 'days');
        $persons_text = $persons . ' ' . ($persons === 1 ? 'person' : 'persons');

        $breakdown = ($q['price_type'] ?? '') === 'per_group'
            ? 'group price for ' . $persons_text . ', ' . $days_text
            : TourPriceService::format($q['unit_price'] ?? 0, $cur) . ' × ' . $persons_text . ', ' . $days_text;

        return [
            'total' => TourPriceService::format($reservation->price_total, $cur),
            'breakdown' => $breakdown,
            'per_person' => TourPriceService::format($q['per_person'] ?? null, $cur),
        ];
    }

    public static function resend_verify_mail(Tour_reservation $reservation): void
    {
        self::send_verify_mail($reservation);
    }

    // ── mails ── a failing mail never breaks the reservation itself

    private static function send_verify_mail(Tour_reservation $reservation): void
    {
        if (!$reservation->email) return;
        $sent = self::safe_send(function () use ($reservation) {
            Notification::route('mail', $reservation->email)->notify(new ReservationVerifyEmail([
                'action_url' => self::confirm_url($reservation),
                'reservation' => self::mail_data($reservation),
            ]));
        }, 'verify', $reservation);

        if ($sent) {
            $reservation->verify_sent_at = now();
            $reservation->verify_sent_count = (int) $reservation->verify_sent_count + 1;
            $reservation->save();
        }
    }

    private static function notify_admin(Tour_reservation $reservation): void
    {
        $site = Site::first();
        $email = ($site && $site->email) ? $site->email : config('mail.from.address');
        if (!$email) return;

        self::safe_send(function () use ($email, $reservation) {
            Notification::route('mail', $email)->notify(new AdminNewReservationNotification(
                self::mail_data($reservation),
                self::admin_url()
            ));
        }, 'admin', $reservation);
    }

    /**
     * After the reservation is confirmed:
     *  1. reserver — "Your reservation is confirmed": the tour, the trip, the price, the guide contacts
     *  2. guide    — "Confirmed reservation": everything about the reserver + trip + price,
     *                to the site address (info@climbing.ge) and to the GUIDE IN CHARGE
     *                (tour_reservations.guide_id; every guide of the tour only when nobody is in charge)
     */
    private static function send_completed_mails(Tour_reservation $reservation): void
    {
        $data = self::mail_data($reservation);

        if ($reservation->email) {
            self::safe_send(function () use ($reservation, $data) {
                Notification::route('mail', $reservation->email)->notify(new ReservationSacsesfulNotification($data));
            }, 'client', $reservation);
        }

        // email => link: info@ opens the admin dashboard, guides their own reservations page
        $site = Site::first();
        $to = [];
        $site_email = ($site && $site->email) ? $site->email : config('mail.from.address');
        $guides = !empty($data['guide']['email']) ? [$data['guide']['email']] : array_filter(array_column($data['guides'], 'email'));
        foreach ($guides as $e) $to[strtolower(trim($e))] = self::guide_url();
        if ($site_email) $to[strtolower(trim($site_email))] = self::admin_url();

        foreach ($to as $email => $url) {
            self::safe_send(function () use ($email, $data, $url) {
                Notification::route('mail', $email)->notify(new ReservationCreatingGuideNotification($data, $url));
            }, 'guide', $reservation);
        }
    }

    private static function safe_send(callable $send, string $kind, Tour_reservation $reservation): bool
    {
        try {
            $send();
            return true;
        } catch (\Throwable $e) {
            Log::warning('Tour reservation mail failed', ['kind' => $kind, 'reservation_id' => $reservation->id, 'error' => $e->getMessage()]);
            return false;
        }
    }
}

<?php

namespace App\Services;

use App\Models\Shop\Rental\Rental;
use App\Models\Shop\Rental\RentalGear;
use App\Models\Shop\Rental\RentalItem;
use App\Models\Shop\Rental\RentalWarehouse;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Gear rental logic — prices per period, stock / availability, saving and
 * returning rentals, the public price list on linked shop services.
 * docs/FUNCTIONS_DOCS/shop/gear_rental.md
 */
class GearRentalService
{
    const CURRENCY = 'GEL';

    /**
     * Price of ONE piece for `$days` days from its period prices [days => price]:
     * - exact period  → its price (2 days = the "2 days" price)
     * - longer than a period → the longest shorter period + every extra day at the
     *   day rate (the 1-day price, or that period's price / its days)
     * - shorter than every period → the shortest period's price (minimum charge)
     * - no prices → null (the admin types the price)
     */
    public static function price_for(array $prices, int $days): ?float
    {
        if ($days < 1 || !$prices) return null;
        ksort($prices);
        if (isset($prices[$days])) return round((float) $prices[$days], 2);

        $lower = array_filter(array_keys($prices), fn ($d) => $d < $days);
        if (!$lower) return round((float) reset($prices), 2);

        $base_days = max($lower);
        $day_rate = isset($prices[1]) ? (float) $prices[1] : (float) $prices[$base_days] / $base_days;
        return round((float) $prices[$base_days] + ($days - $base_days) * $day_rate, 2);
    }

    public static function price_map(RentalGear $gear): array
    {
        return $gear->prices->mapWithKeys(fn ($p) => [(int) $p->days => (float) $p->price])->all();
    }

    /** gear_id => pieces out on active rentals (one warehouse, or all with null) */
    public static function rented_map(?int $warehouse_id = null, ?int $except_rental_id = null): array
    {
        return RentalItem::query()
            ->join('rentals', 'rentals.id', '=', 'rental_items.rental_id')
            ->where('rentals.status', Rental::ACTIVE)
            ->whereNotNull('rental_items.rental_gear_id')
            ->when($warehouse_id, fn ($q) => $q->where('rentals.rental_warehouse_id', $warehouse_id))
            ->when($except_rental_id, fn ($q) => $q->where('rentals.id', '!=', $except_rental_id))
            ->groupBy('rental_items.rental_gear_id')
            ->selectRaw('rental_items.rental_gear_id as gear_id, SUM(rental_items.quantity) as qty')
            ->pluck('qty', 'gear_id')
            ->map(fn ($v) => (int) $v)
            ->all();
    }

    /** gear_id => pieces the warehouse owns */
    public static function stock_map(int $warehouse_id): array
    {
        return DB::table('rental_warehouse_gear')
            ->where('rental_warehouse_id', $warehouse_id)
            ->pluck('quantity', 'rental_gear_id')
            ->map(fn ($v) => (int) $v)
            ->all();
    }

    /**
     * Admin gear list: prices, stock / rented / available per warehouse and in
     * total, and who has it right now.
     */
    public static function gear_list(): array
    {
        $warehouses = RentalWarehouse::orderByDesc('general')->orderBy('name')->get();
        $stock = DB::table('rental_warehouse_gear')->get()->groupBy('rental_gear_id');

        $out_rows = RentalItem::query()
            ->join('rentals', 'rentals.id', '=', 'rental_items.rental_id')
            ->where('rentals.status', Rental::ACTIVE)
            ->whereNotNull('rental_items.rental_gear_id')
            ->orderBy('rentals.due_at')
            ->get([
                'rental_items.rental_gear_id', 'rental_items.quantity', 'rentals.id as rental_id',
                'rentals.name', 'rentals.surname', 'rentals.due_at', 'rentals.rental_warehouse_id',
            ])
            ->groupBy('rental_gear_id');

        return RentalGear::with('prices')->orderBy('sort_order')->orderBy('us_name')->get()
            ->map(function (RentalGear $g) use ($warehouses, $stock, $out_rows) {
                $mine = $stock->get($g->id, collect())->keyBy('rental_warehouse_id');
                $out = $out_rows->get($g->id, collect());

                $per_wh = $warehouses->map(function ($w) use ($mine, $out) {
                    $qty = (int) optional($mine->get($w->id))->quantity;
                    $rented = (int) $out->where('rental_warehouse_id', $w->id)->sum('quantity');
                    return ['id' => $w->id, 'name' => $w->name, 'general' => $w->general,
                        'quantity' => $qty, 'rented' => $rented, 'available' => max(0, $qty - $rented)];
                })->values();

                $total = (int) $per_wh->sum('quantity');
                $rented = (int) $out->sum('quantity');

                return array_merge($g->toArray(), [
                    'prices' => $g->prices->map(fn ($p) => ['days' => $p->days, 'price' => $p->price])->values(),
                    'warehouses' => $per_wh,
                    'total_quantity' => $total,
                    'rented' => $rented,
                    'available' => max(0, $total - $rented),
                    'is_rented' => $rented > 0,
                    'renters' => $out->map(fn ($r) => [
                        'rental_id' => $r->rental_id,
                        'name' => trim($r->name . ' ' . $r->surname),
                        'quantity' => (int) $r->quantity,
                        'due_at' => $r->due_at,
                    ])->values(),
                ]);
            })->all();
    }

    /**
     * Price lines for the rental form: [{gear_id, quantity, unit_price?}] + days →
     * suggested unit price, line total, available pieces in that warehouse.
     */
    public static function quote(array $lines, int $days, ?int $warehouse_id, ?int $except_rental_id = null): array
    {
        $gear = RentalGear::with('prices')->whereIn('id', collect($lines)->pluck('gear_id')->filter())->get()->keyBy('id');
        $stock = $warehouse_id ? self::stock_map($warehouse_id) : [];
        $rented = $warehouse_id ? self::rented_map($warehouse_id, $except_rental_id) : [];

        $rows = [];
        $subtotal = 0;
        foreach ($lines as $line) {
            $g = $gear->get((int) ($line['gear_id'] ?? 0));
            if (!$g) continue;
            $qty = max(1, (int) ($line['quantity'] ?? 1));
            $suggested = self::price_for(self::price_map($g), $days);
            $unit = isset($line['unit_price']) && $line['unit_price'] !== '' && $line['unit_price'] !== null
                ? round((float) $line['unit_price'], 2) : $suggested;
            $line_total = $unit === null ? null : round($unit * $qty, 2);
            $subtotal += (float) $line_total;
            $rows[] = [
                'gear_id' => $g->id,
                'gear_name' => $g->us_name,
                'quantity' => $qty,
                'suggested_price' => $suggested,
                'unit_price' => $unit,
                'line_total' => $line_total,
                'available' => max(0, ($stock[$g->id] ?? 0) - ($rented[$g->id] ?? 0)),
                'deposit' => $g->deposit,
            ];
        }

        return ['lines' => $rows, 'subtotal' => round($subtotal, 2), 'currency' => self::CURRENCY];
    }

    /**
     * Create (or edit) a rental. Checks stock in the warehouse inside a
     * transaction (stock rows locked, so two people can't rent the last piece),
     * computes prices, links the renter to their account (given user_id, or the
     * account with the same e-mail).
     *
     * @throws ValidationException
     */
    public static function save(array $data, ?Rental $rental, ?User $by): Rental
    {
        return DB::transaction(function () use ($data, $rental, $by) {
            $warehouse_id = (int) $data['rental_warehouse_id'];
            $days = (int) $data['days'];

            // lock this warehouse's stock rows for the check
            DB::table('rental_warehouse_gear')->where('rental_warehouse_id', $warehouse_id)->lockForUpdate()->get();

            // the same gear twice in the form → one line
            $lines = collect($data['items'])
                ->groupBy(fn ($l) => (int) $l['gear_id'])
                ->map(fn ($group, $gear_id) => [
                    'gear_id' => $gear_id,
                    'quantity' => (int) $group->sum(fn ($l) => (int) $l['quantity']),
                    'unit_price' => $group->first()['unit_price'] ?? null,
                ])->values()->all();

            $quote = self::quote($lines, $days, $warehouse_id, $rental?->id);

            // a returned / cancelled rental holds no stock
            $holds_stock = !$rental || $rental->status === Rental::ACTIVE;
            $errors = [];
            foreach ($quote['lines'] as $i => $l) {
                if ($holds_stock && $l['quantity'] > $l['available']) {
                    $errors["items.$i.quantity"] = ["Only {$l['available']} × {$l['gear_name']} available in this warehouse."];
                }
                if ($l['unit_price'] === null) {
                    $errors["items.$i.unit_price"] = ["{$l['gear_name']} has no price for {$days} days — enter one."];
                }
            }
            if (count($quote['lines']) !== count($lines)) $errors['items'] = ['Unknown gear in the list.'];
            if ($errors) throw ValidationException::withMessages($errors);

            $start = Carbon::parse($data['start_at']);
            $discount = round(max(0, (float) ($data['discount'] ?? 0)), 2);

            $rental = $rental ?? new Rental(['status' => Rental::ACTIVE]);
            $rental->fill([
                'rental_warehouse_id' => $warehouse_id,
                'name' => $data['name'],
                'surname' => $data['surname'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'],
                'id_number' => $data['id_number'] ?? null,
                'start_at' => $start,
                'days' => $days,
                'due_at' => $start->copy()->addDays($days),
                'subtotal' => $quote['subtotal'],
                'discount' => min($discount, $quote['subtotal']),
                'total' => round(max(0, $quote['subtotal'] - $discount), 2),
                'deposit' => round(max(0, (float) ($data['deposit'] ?? 0)), 2),
                'notes' => $data['notes'] ?? null,
            ]);
            $rental->user_id = self::resolve_user_id($data);
            // "Paid for the rental period" in the form: keep the first payment time
            if (array_key_exists('paid', $data) && $data['paid'] !== null) {
                $rental->paid_at = $data['paid'] ? ($rental->paid_at ?? now()) : null;
            }
            if (!$rental->exists) $rental->created_by = $by?->id;
            $rental->save();

            $rental->items()->delete();
            foreach ($quote['lines'] as $l) {
                $rental->items()->create([
                    'rental_gear_id' => $l['gear_id'],
                    'gear_name' => $l['gear_name'],
                    'quantity' => $l['quantity'],
                    'unit_price' => $l['unit_price'],
                    'line_total' => $l['line_total'],
                ]);
            }

            return $rental->fresh(['items', 'warehouse', 'user:id,name,surname,email,phone_number,image']);
        });
    }

    // chosen account, else the registered account with this e-mail
    private static function resolve_user_id(array $data): ?int
    {
        if (!empty($data['user_id']) && User::whereKey($data['user_id'])->exists()) return (int) $data['user_id'];
        if (!empty($data['email'])) return User::where('email', $data['email'])->value('id');
        return null;
    }

    public static function mark_returned(Rental $rental, bool $deposit_returned): Rental
    {
        $rental->status = Rental::RETURNED;
        $rental->returned_at = now();
        $rental->deposit_returned = $deposit_returned;
        $rental->save();
        return $rental;
    }

    /** Dashboard numbers */
    public static function summary(): array
    {
        $now = now();
        $active = Rental::active();
        $month_start = $now->copy()->startOfMonth();

        return [
            'active' => (clone $active)->count(),
            'overdue' => (clone $active)->where('due_at', '<', $now)->count(),
            'due_today' => (clone $active)->whereBetween('due_at', [$now->copy()->startOfDay(), $now->copy()->endOfDay()])->count(),
            'unpaid' => (clone $active)->whereNull('paid_at')->count(),
            'pieces_out' => (int) RentalItem::whereIn('rental_id', (clone $active)->select('id'))->sum('quantity'),
            'pieces_total' => (int) DB::table('rental_warehouse_gear')->sum('quantity'),
            'returned_month' => Rental::where('status', Rental::RETURNED)->where('returned_at', '>=', $month_start)->count(),
            'revenue_month' => round((float) Rental::where('status', '!=', Rental::CANCELLED)->where('start_at', '>=', $month_start)->sum('total'), 2),
            'currency' => self::CURRENCY,
        ];
    }

    /**
     * Public price list for a shop service linked to gear rental
     * (services.link_type = 'gear_rental'; link_id = warehouse, null = the general one).
     * Only gear with `show_in_shop`, at least one price and stock in that warehouse;
     * no renter data and no availability / stock counts.
     */
    public static function public_price_list(?int $warehouse_id, string $lang): ?array
    {
        $warehouse = $warehouse_id ? RentalWarehouse::find($warehouse_id) : RentalWarehouse::general();
        if (!$warehouse) return null;

        $stock = self::stock_map($warehouse->id);

        $gear = RentalGear::with('prices')->where('show_in_shop', true)
            ->orderBy('sort_order')->orderBy('us_name')->get()
            ->filter(fn ($g) => $g->prices->isNotEmpty() && ($stock[$g->id] ?? 0) > 0);

        $periods = $gear->flatMap(fn ($g) => $g->prices->pluck('days'))->unique()->sort()->values();

        return [
            'type' => 'gear_rental',
            'currency' => self::CURRENCY,
            'periods' => $periods,
            'gear' => $gear->map(fn ($g) => [
                'id' => $g->id,
                'name' => $g->name_for($lang),
                'description' => $g->description_for($lang),
                'category' => $g->category,
                'deposit' => $g->deposit,
                'prices' => $g->prices->mapWithKeys(fn ($p) => [$p->days => $p->price]),
            ])->values(),
        ];
    }
}

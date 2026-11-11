<?php

namespace App\Http\Controllers\Api\User\Admin\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop\Rental\Rental;
use App\Models\Shop\Rental\RentalGear;
use App\Models\Shop\Rental\RentalWarehouse;
use App\Models\User;
use App\Services\GearRentalService;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Gear rental admin API (Shop dashboard → Gear rental), permission subject `gear_rental`.
 * docs/FUNCTIONS_DOCS/shop/gear_rental.md
 */
class GearRentalController extends Controller
{
    const ID_CARD_DIR = 'rental_id_cards';   // on the private `local` disk (storage/app), never public

    // ───────────── dashboard ─────────────

    public function summary()
    {
        if ($auth = PermissionService::authorize('gear_rental', 'show')) return $auth;
        return response()->json(GearRentalService::summary());
    }

    // ───────────── warehouses ─────────────

    public function get_warehouses()
    {
        if ($auth = PermissionService::authorize('gear_rental', 'show')) return $auth;

        $stock = DB::table('rental_warehouse_gear')->groupBy('rental_warehouse_id')
            ->selectRaw('rental_warehouse_id, SUM(quantity) as pieces, COUNT(*) as kinds')->get()->keyBy('rental_warehouse_id');
        $active = Rental::active()->groupBy('rental_warehouse_id')
            ->selectRaw('rental_warehouse_id, COUNT(*) as n')->pluck('n', 'rental_warehouse_id');

        return RentalWarehouse::orderByDesc('general')->orderBy('name')->get()->map(fn ($w) => array_merge($w->toArray(), [
            'pieces' => (int) optional($stock->get($w->id))->pieces,
            'kinds' => (int) optional($stock->get($w->id))->kinds,
            'active_rentals' => (int) ($active[$w->id] ?? 0),
        ]));
    }

    public function save_warehouse(Request $request, $id = null)
    {
        if ($auth = PermissionService::authorize('gear_rental', $id ? 'edit' : 'add')) return $auth;

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'general' => 'boolean',
            'address' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:2000',
        ]);

        $warehouse = $id ? RentalWarehouse::findOrFail($id) : new RentalWarehouse();

        DB::transaction(function () use ($warehouse, $data) {
            // only one general warehouse; the first one ever is general
            if (!empty($data['general'])) {
                RentalWarehouse::where('general', true)->when($warehouse->exists, fn ($q) => $q->where('id', '!=', $warehouse->id))->update(['general' => false]);
            } elseif (!RentalWarehouse::where('general', true)->when($warehouse->exists, fn ($q) => $q->where('id', '!=', $warehouse->id))->exists()) {
                $data['general'] = true;
            }
            $warehouse->fill($data)->save();
        });

        return response()->json($warehouse->fresh(), $id ? 200 : 201);
    }

    public function del_warehouse($id)
    {
        if ($auth = PermissionService::authorize('gear_rental', 'del')) return $auth;

        $warehouse = RentalWarehouse::findOrFail($id);
        if ($warehouse->rentals()->where('status', Rental::ACTIVE)->exists()) {
            return response()->json(['message' => 'This warehouse has gear out on active rentals — return them first.'], 422);
        }
        $was_general = $warehouse->general;
        $warehouse->delete();   // stock rows cascade; old rentals keep their history (warehouse → null)
        if ($was_general && ($next = RentalWarehouse::orderBy('id')->first())) $next->update(['general' => true]);

        return response()->json(['deleted' => true]);
    }

    // ───────────── gear ─────────────

    public function get_gear()
    {
        if ($auth = PermissionService::authorize('gear_rental', 'show')) return $auth;
        return response()->json(GearRentalService::gear_list());
    }

    public function save_gear(Request $request, $id = null)
    {
        if ($auth = PermissionService::authorize('gear_rental', $id ? 'edit' : 'add')) return $auth;

        $data = $request->validate([
            'us_name' => 'required|string|max:255',
            'ka_name' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:120',
            'code' => 'nullable|string|max:120',
            'deposit' => 'nullable|numeric|min:0|max:100000',
            'show_in_shop' => 'boolean',
            'sort_order' => 'nullable|integer|min:0|max:100000',
            'note' => 'nullable|string|max:2000',
            'prices' => 'array',
            'prices.*.days' => 'required|integer|min:1|max:365|distinct',
            'prices.*.price' => 'required|numeric|min:0|max:100000',
            'stock' => 'array',
            'stock.*.warehouse_id' => 'required|integer|exists:rental_warehouses,id|distinct',
            'stock.*.quantity' => 'required|integer|min:0|max:100000',
        ]);

        $gear = $id ? RentalGear::findOrFail($id) : new RentalGear();

        // stock can't drop below what is out on rental right now
        if (isset($data['stock']) && $gear->exists) {
            $errors = [];
            foreach ($data['stock'] as $i => $s) {
                $out = GearRentalService::rented_map((int) $s['warehouse_id'])[$gear->id] ?? 0;
                if ((int) $s['quantity'] < $out) $errors["stock.$i.quantity"] = ["{$out} pieces are out on rental in this warehouse."];
            }
            if ($errors) return response()->json(['message' => 'Validation failed', 'errors' => $errors], 422);
        }

        DB::transaction(function () use ($gear, $data) {
            $gear->fill(collect($data)->except(['prices', 'stock'])->all());
            $gear->sort_order = (int) ($data['sort_order'] ?? 0);
            $gear->save();

            if (array_key_exists('prices', $data)) {
                $gear->prices()->delete();
                foreach ($data['prices'] as $p) $gear->prices()->create(['days' => (int) $p['days'], 'price' => round((float) $p['price'], 2)]);
            }
            if (array_key_exists('stock', $data)) {
                $gear->warehouses()->sync(collect($data['stock'])->mapWithKeys(fn ($s) => [(int) $s['warehouse_id'] => ['quantity' => (int) $s['quantity']]])->all());
            }
        });

        return response()->json(collect(GearRentalService::gear_list())->firstWhere('id', $gear->id), $id ? 200 : 201);
    }

    public function del_gear($id)
    {
        if ($auth = PermissionService::authorize('gear_rental', 'del')) return $auth;

        $gear = RentalGear::findOrFail($id);
        if ((GearRentalService::rented_map()[$gear->id] ?? 0) > 0) {
            return response()->json(['message' => 'This gear is out on an active rental — return it first.'], 422);
        }
        $gear->delete();   // prices + stock cascade; rental lines keep the name (gear → null)

        return response()->json(['deleted' => true]);
    }

    // ───────────── rentals ─────────────

    // scope: active (incl. overdue) | history (returned + cancelled) | all
    public function get_rentals(Request $request)
    {
        if ($auth = PermissionService::authorize('gear_rental', 'show')) return $auth;

        $scope = $request->query('scope', 'active');
        $rentals = Rental::with(['items', 'warehouse:id,name', 'user:id,name,surname'])
            ->when($scope === 'active', fn ($q) => $q->where('status', Rental::ACTIVE)->orderBy('due_at'))
            ->when($scope === 'history', fn ($q) => $q->where('status', '!=', Rental::ACTIVE)->orderByDesc('updated_at'))
            ->when(!in_array($scope, ['active', 'history'], true), fn ($q) => $q->orderByDesc('id'))
            ->when($request->query('user_id'), fn ($q, $uid) => $q->where('user_id', $uid))
            ->limit(1000)->get();

        return $rentals->map(fn ($r) => $this->row($r));
    }

    public function get_rental($id)
    {
        if ($auth = PermissionService::authorize('gear_rental', 'show')) return $auth;

        $r = Rental::with(['items', 'warehouse:id,name', 'user:id,name,surname,email,phone_number,image', 'creator:id,name,surname'])->findOrFail($id);
        return response()->json($this->row($r) + ['creator' => $r->creator]);
    }

    public function quote(Request $request)
    {
        if ($auth = PermissionService::authorize('gear_rental', 'show')) return $auth;

        $data = $request->validate([
            'days' => 'required|integer|min:1|max:365',
            'rental_warehouse_id' => 'nullable|integer',
            'rental_id' => 'nullable|integer',
            'items' => 'array',
            'items.*.gear_id' => 'required|integer',
            'items.*.quantity' => 'nullable|integer|min:1',
            'items.*.unit_price' => 'nullable|numeric|min:0',
        ]);

        return response()->json(GearRentalService::quote($data['items'] ?? [], (int) $data['days'], $data['rental_warehouse_id'] ?? null, $data['rental_id'] ?? null));
    }

    // multipart: `data` (JSON) + optional `id_card` image
    public function save_rental(Request $request, $id = null)
    {
        if ($auth = PermissionService::authorize('gear_rental', $id ? 'edit' : 'add')) return $auth;

        $input = is_string($request->input('data')) ? json_decode($request->input('data'), true) : $request->input('data', $request->except('id_card'));
        $request->validate(['id_card' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:10240']);

        $data = validator($input ?: [], [
            'rental_warehouse_id' => 'required|integer|exists:rental_warehouses,id',
            'user_id' => 'nullable|integer',
            'name' => 'required|string|max:120',
            'surname' => 'required|string|max:120',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:50',
            'id_number' => 'nullable|string|max:60',
            'start_at' => 'required|date',
            'days' => 'required|integer|min:1|max:365',
            'discount' => 'nullable|numeric|min:0',
            'deposit' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:5000',
            'paid' => 'nullable|boolean',   // paid for the rental period (missing = keep as it is)
            'items' => 'required|array|min:1',
            'items.*.gear_id' => 'required|integer|exists:rental_gear,id',
            'items.*.quantity' => 'required|integer|min:1|max:1000',
            'items.*.unit_price' => 'nullable|numeric|min:0',
        ])->validate();

        $rental = $id ? Rental::findOrFail($id) : null;
        $rental = GearRentalService::save($data, $rental, $request->user());

        if ($request->hasFile('id_card')) $this->store_id_card($rental, $request->file('id_card'));

        return response()->json($this->row($rental->fresh(['items', 'warehouse:id,name', 'user:id,name,surname'])), $id ? 200 : 201);
    }

    public function return_rental(Request $request, $id)
    {
        if ($auth = PermissionService::authorize('gear_rental', 'edit')) return $auth;

        $rental = Rental::findOrFail($id);
        if ($rental->status !== Rental::ACTIVE) return response()->json(['message' => 'This rental is not active.'], 422);

        GearRentalService::mark_returned($rental, $request->boolean('deposit_returned', true));
        if ($request->boolean('paid') && !$rental->paid_at) { $rental->paid_at = now(); $rental->save(); }

        return response()->json($this->row($rental->fresh(['items', 'warehouse:id,name', 'user:id,name,surname'])));
    }

    public function cancel_rental($id)
    {
        if ($auth = PermissionService::authorize('gear_rental', 'edit')) return $auth;

        $rental = Rental::findOrFail($id);
        if ($rental->status !== Rental::ACTIVE) return response()->json(['message' => 'This rental is not active.'], 422);
        $rental->status = Rental::CANCELLED;
        $rental->save();

        return response()->json($this->row($rental->fresh(['items', 'warehouse:id,name', 'user:id,name,surname'])));
    }

    // paid: true / false
    public function set_paid(Request $request, $id)
    {
        if ($auth = PermissionService::authorize('gear_rental', 'edit')) return $auth;

        $rental = Rental::findOrFail($id);
        $rental->paid_at = $request->boolean('paid') ? ($rental->paid_at ?? now()) : null;
        $rental->save();

        return response()->json(['paid_at' => $rental->paid_at]);
    }

    public function del_rental($id)
    {
        if ($auth = PermissionService::authorize('gear_rental', 'del')) return $auth;

        $rental = Rental::findOrFail($id);
        $this->delete_id_card_file($rental);
        $rental->delete();

        return response()->json(['deleted' => true]);
    }

    public function bulk_delete(Request $request)
    {
        if ($auth = PermissionService::authorize('gear_rental', 'del')) return $auth;
        $request->validate(['ids' => 'required|array|min:1', 'ids.*' => 'integer']);

        $n = 0;
        Rental::whereIn('id', $request->ids)->get()->each(function ($r) use (&$n) {
            $this->delete_id_card_file($r);
            $r->delete();
            $n++;
        });

        return response()->json(['success' => true, 'count' => $n]);
    }

    // ───────────── ID card photo (private) ─────────────

    public function id_card($id)
    {
        if ($auth = PermissionService::authorize('gear_rental', 'show')) return $auth;

        $rental = Rental::findOrFail($id);
        $path = $rental->getRawOriginal('id_card_image');
        if (!$path || !Storage::disk('local')->exists($path)) abort(404);

        return Storage::disk('local')->response($path, null, [
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function upload_id_card(Request $request, $id)
    {
        if ($auth = PermissionService::authorize('gear_rental', 'edit')) return $auth;
        $request->validate(['id_card' => 'required|image|mimes:jpeg,jpg,png,webp|max:10240']);

        $rental = Rental::findOrFail($id);
        $this->store_id_card($rental, $request->file('id_card'));

        return response()->json(['has_id_card' => true]);
    }

    public function del_id_card($id)
    {
        if ($auth = PermissionService::authorize('gear_rental', 'edit')) return $auth;

        $rental = Rental::findOrFail($id);
        $this->delete_id_card_file($rental);
        $rental->forceFill(['id_card_image' => null])->save();

        return response()->json(['has_id_card' => false]);
    }

    // ───────────── renter search (registered users) ─────────────

    public function search_users(Request $request)
    {
        if ($auth = PermissionService::authorize('gear_rental', 'show')) return $auth;

        $q = trim((string) $request->query('q', ''));
        if (mb_strlen($q) < 2) return [];
        $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $q) . '%';

        return User::where(fn ($w) => $w->where('name', 'like', $like)->orWhere('surname', 'like', $like)
                ->orWhere('email', 'like', $like)->orWhere('phone_number', 'like', $like)
                ->orWhereRaw("CONCAT(name, ' ', surname) LIKE ?", [$like]))
            ->limit(10)->get(['id', 'name', 'surname', 'email', 'phone_number', 'image']);
    }

    // ───────────── helpers ─────────────

    private function store_id_card(Rental $rental, $file): void
    {
        $this->delete_id_card_file($rental);
        $path = $file->storeAs(self::ID_CARD_DIR, $rental->id . '_' . Str::random(24) . '.' . strtolower($file->extension() ?: 'jpg'), 'local');
        $rental->forceFill(['id_card_image' => $path])->save();
    }

    private function delete_id_card_file(Rental $rental): void
    {
        $old = $rental->getRawOriginal('id_card_image');
        if ($old && Storage::disk('local')->exists($old)) Storage::disk('local')->delete($old);
    }

    // table / modal row
    private function row(Rental $r): array
    {
        return array_merge($r->toArray(), [
            'renter' => trim($r->name . ' ' . $r->surname),
            'warehouse_name' => optional($r->warehouse)->name,
            'gear_summary' => $r->items->map(fn ($i) => $i->quantity . '× ' . $i->gear_name)->implode(', '),
            'pieces' => (int) $r->items->sum('quantity'),
            'is_paid' => (bool) $r->paid_at,
            'is_registered' => (bool) $r->user_id,
            'currency' => GearRentalService::CURRENCY,
        ]);
    }
}

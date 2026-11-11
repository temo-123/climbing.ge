# Gear rental (admin)

Staff rent out climbing gear from one or more rental warehouses. The panel keeps the gear catalogue with prices per rental period, stock per warehouse, every rental with the renter's details and ID card photo, and shows at a glance what is out, who has it and what is overdue. A shop service page can show the rental price list.

- **Where:** user panel → Shop dashboard → **Gear rental** (`/gear_rental`, route `gearRental`).
- **Permission:** `gear_rental` › `show` / `add` / `edit` / `del` (granted to the `admin` role by `2026_11_11_120100_sync_gear_rental_permissions_with_admin_role.php`). Give it to other roles in Users & permissions.
- **Not for customers:** there is no public booking. The public side is only the price list on a linked service page.

## Data

Migration `2026_11_11_120000_create_gear_rental_tables.php`, models in `app/Models/Shop/Rental/`.

| Table | What |
|---|---|
| `rental_warehouses` | `name`, `general` (exactly one; the first one created is general, deleting it passes the flag on), `address`, `note` |
| `rental_gear` | `us_name`, `ka_name`, `category`, `code` (inventory / barcode), `deposit` (suggested per piece), `show_in_shop`, `sort_order`, `us_description` / `ka_description` (short description, max 500 — shown in the admin gear list (Description column) and under the name in the shop price list, KA falls back to EN; was `note` until migration `2026_11_12_120000`) |
| `rental_gear_prices` | `rental_gear_id`, `days`, `price` — one row per period (unique per gear + days) |
| `rental_warehouse_gear` | stock: `rental_warehouse_id`, `rental_gear_id`, `quantity` |
| `rentals` | `rental_warehouse_id` (→ null if the warehouse is deleted), `user_id` (→ null if the account is deleted), `name`, `surname`, `email`, `phone`, `id_number`, `id_card_image` (private path), `start_at`, `days`, `due_at` (= start + days), `returned_at`, `status` (`active` / `returned` / `cancelled`), `subtotal`, `discount`, `total`, `deposit`, `deposit_returned`, `paid_at`, `notes`, `created_by` |
| `rental_items` | `rental_id`, `rental_gear_id` (→ null if the gear is deleted), `gear_name` (kept), `quantity`, `unit_price` (one piece for the whole period), `line_total` |

All money is GEL (₾). **Overdue** is computed, not stored: `status = active` and `due_at` in the past (`Rental::is_overdue`).

## Rules

All in `app/Services/GearRentalService.php`.

- **Price of one piece for N days** (`price_for`):
  - exact period → its price (2 days = the "2 days" price);
  - longer than a period → the longest shorter period + each extra day at the 1-day price (or that period's price / its days when there is no 1-day price). Example: 1 d 20 ₾, 2 d 35 ₾, 7 d 100 ₾ → 4 days = 35 + 2 × 20 = 75 ₾, 9 days = 140 ₾;
  - shorter than every period → the shortest period's price (minimum charge);
  - no prices → none: the form asks for a typed price.
  
  Staff can always type another price per line; the list price stays visible under it.
- **Availability** = warehouse stock − pieces on **active** rentals from that warehouse. Future-dated rentals hold their pieces from the moment they're saved. Returned and cancelled rentals hold nothing.
- **Saving a rental** runs in a transaction with the warehouse's stock rows locked, so two people can't rent the last piece. More than available → `422 items.N.quantity`. When a rental is edited, its own pieces count as free. The same gear twice in the form becomes one line.
- **Account link:** a user picked in the form (search by name / e-mail / phone, `search_users`, returns only `id, name, surname, email, phone_number, image`) or, if none is picked, the account with the same e-mail. The rental shows in that user's **User info** modal → Shop tab → "Gear rentals" (`AdminUserOverviewService::shop`).
- **Stock edits** can't go below the pieces out in that warehouse; gear that is out can't be deleted, nor a warehouse with active rentals (`422` + message).
- **Deposit:** suggested in the form as Σ gear deposit × quantity until typed; on return, "deposit given back" is ticked by default.

## ID card photo

- Upload a file or **take a photo** (`IdCardCapture.vue`): `getUserMedia` (webcam, or the back camera on a phone); without the camera API, the file input with `capture="environment"` opens the phone's camera app. The picture is shrunk in the browser to max 1800 px JPEG (an unreadable format such as HEIC is sent as is).
- Server: `image`, jpeg / png / webp, max 10 MB. Stored on the **private `local` disk** under `storage/app/rental_id_cards/{rental_id}_{random}.{ext}` (ignored by git, not under `public/`). The path is hidden from JSON (`Rental::$hidden`); rows carry `has_id_card`.
- Viewing: `GET set_gear_rental/id_card/{id}` (`gear_rental › show`, `Cache-Control: no-store, private`), loaded by `IdCardImage.vue` with the Bearer token into an object URL.
- Replacing deletes the old file; deleting the photo or the rental deletes the file.
- **Privacy:** these are ID document photos. Only staff with `gear_rental › show` can open them. Keep the privacy policy in line before using this with customers (what is stored, why, how long).

## Panel page (`resources/js/components/user/pages/gear_rental/`)

`GearRentalPage.vue`: title + **New rental** + refresh, a start hint while there is no warehouse, six number cards (out on rental, overdue — orange when > 0, due back today, pieces out / total, this month's revenue, unpaid active), then three sections (pills with counts, kept in `?section=`):

- **Rentals** — tabs *Active* (soonest due first) and *Returned & cancelled*. Columns: renter (opens details), phone, gear ("2× Harness, 1× Helmet"), from · period, due back, total (+ deposit), paid ✓, account ✓, ID card ✓, status, details / edit / delete. Rows: red = overdue, yellow = due back today, grey = closed. Quick filters come from the ✓ columns.
- **Gear** — prices ("1 day 20 ₾ · 2 days 35 ₾"), total, out, available, per warehouse (free / total), who has it, rented ✓, on the shop ✓. Rows: yellow = none left, accent = some out.
- **Warehouses** — general ✓, address, gear kinds, pieces, active rentals.

Modals:

- `RentalFormModal.vue` — 1 renter (account search, name*, surname*, phone*, e-mail, personal no., ID card), 2 period (warehouse, start, days with 1 / 2 / 3 / 5 / 7 / 14-day chips, "due back …"), 3 gear lines (gear with "N free", qty, price per piece for the period with the list price as placeholder, line total; live `POST quote`), 4 money (discount, deposit, subtotal / total, notes, and the **Paid for the rental period** switch — "{total} for {N days}"; on saves `paid_at = now` (the first payment time is kept on later edits), off clears it; the request field `paid` is optional, without it the paid state is left as it is). Server errors show on their fields.
- `RentalDetailsModal.vue` — status band (active / overdue since … / returned …) with paid and deposit badges, renter, account link, ID card (view, replace, delete), period, gear table with totals, notes; **Gear is back** box (paid ✓, deposit given back ✓ → `return_rental`); footer: delete, cancel rental, mark paid / unpaid, edit.
- `GearFormModal.vue` — names EN / KA, short descriptions EN / KA, category (suggests existing ones), code, deposit, show on the shop, order, prices per period (rows days + price with "x ₾ / day", + preset periods), stock for every warehouse with "N out now", who has it.
- `RentalWarehouseModal.vue` — name, general, address, note.

The menu link gets the alert badges (`services/menuBadges.js`): orange = overdue rentals, accent = due back today.

## Shop service page

`services` got `link_type` (null | `gear_rental` | `wall_calculator`), `link_id` (gear rental: the warehouse), `link_options` (JSON, free for future links). New link kinds: add the key to `Service::LINK_TYPES`, a branch in `ServicesService::linked_info()`, an entry in `ServicesController::get_link_targets()` and the part on `ServicePage.vue`.

**Climbing wall calculator** (`wall_calculator`): `linked = { type: 'wall_calculator' }` and the service page shows the whole calculator under the text — the same component as the `/climbing_wall_colculator` page (`wall_colculator/ClimbingWallPriceColculatePage.vue`) with `embedded`: its title is an h2 (the service has the h1), it sets no page meta, and its outer padding is smaller. It's loaded as its own chunk only on such a page (`defineAsyncComponent`). Prices still come from Site Options → Coefficients ([COEFFICIENTS.md](../../COEFFICIENTS.md)).

- Admin service add / edit form → global tab → **Linked info on the page** (`ServiceLinkFieldsComponent.vue`, options from `GET set_service/get_link_targets`): Nothing / Gear rental prices + which warehouse (default: the general one). Saved by `ServicesController::save_link()` after the normal save (unknown types and warehouses are dropped).
- Admin **Services** list (`servicesListComponent.vue`) has a **Linked info** column: "—", "Climbing wall calculator" or "Gear rental prices · <warehouse>" (the general one when `link_id` is empty).
- `GET get_service/get_local_service_in_page/{lang}/{url_title}` now returns `linked`: `{ type: 'gear_rental', currency, periods: [1, 2, 7], gear: [{ id, name (KA name on /ka), description (KA falls back to EN), category, deposit, prices: { days: price } }] }` — only gear with **Show on the shop**, at least one price and stock in that warehouse. No renter data and no availability / stock counts.
- `ServicePage.vue` shows it after the text (`shop/items/RentalPriceListComponent.vue`): a table gear × periods, short description under the name, deposit column when any gear has one (no availability column), scrolls sideways on phones.

## API (`routes/api/admin/set_shop_routes.php`, prefix `set_gear_rental`, `GearRentalController`)

| | |
|---|---|
| `GET summary` | dashboard numbers |
| `GET warehouses`, `POST add_warehouse`, `POST edit_warehouse/{id}`, `DELETE del_warehouse/{id}` | warehouses |
| `GET gear`, `POST add_gear`, `POST edit_gear/{id}`, `DELETE del_gear/{id}` | gear (with `prices[]`, `stock[]`) |
| `GET rentals?scope=active\|history\|all[&user_id=]`, `GET rental/{id}` | rentals |
| `POST quote` | prices + availability for the form |
| `POST add_rental`, `POST edit_rental/{id}` | multipart: `data` (JSON) + optional `id_card` |
| `POST return_rental/{id}` (`paid`, `deposit_returned`), `POST cancel_rental/{id}`, `POST set_paid/{id}` (`paid`) | state |
| `DELETE del_rental/{id}`, `POST bulk_delete` | delete (with the photo) |
| `GET id_card/{id}`, `POST upload_id_card/{id}`, `DELETE del_id_card/{id}` | ID card photo |
| `GET search_users?q=` | renter account search |

Tests: `tests/Feature/GearRentalTest.php` (prices, general warehouse, stock / availability / overdue, edit, cancel / return, private ID card, permissions, user search fields, linked service page).

# Coefficients — admin-editable prices, periods & weights

**Admin page:** `user.climbing.ge/coefficients` (left menu → **Site Options → Coefficients**)
**Permission subject:** `coefficient` (`show` / `add` / `edit` / `del`)

Coefficients are the numbers the site uses for business logic: climbing wall
calculator prices, shop delivery periods, climber points weights. They used to
be hardcoded in several JS/PHP files. Now each one is a row in the
`coefficients` table that an admin can change without a code deploy.

---

## Table of Contents

- [How it works](#how-it-works)
- [All coefficients](#all-coefficients)
- [Using the admin page](#using-the-admin-page)
- [Database](#database)
- [API](#api)
- [Reading coefficients in code](#reading-coefficients-in-code)
- [Adding a new coefficient](#adding-a-new-coefficient)
- [Deployment](#deployment)
- [Troubleshooting](#troubleshooting)

---

## How it works

```
config/coefficients.php  (defaults for every slug)
          +
coefficients table       (admin overrides, by slug)
          │
          ▼
CoefficientService::all()   — merged map, cached 10 min
          │
          ├──► PHP: CoefficientService::get('slug')
          │        (User points, delivery days API, wall PDF export)
          │
          └──► partials/coefficients.blade.php
                  <script>window.__COEFFICIENTS__ = {...}</script>
                  (printed into the HTML of all 6 subdomains)
                        │
                        ▼
               JS: coef('slug') / deliveryDays(bool)
               (wall calculator, product page, checkout, order modals…)
```

- **No API call on page load.** The server puts the whole slug → value map
  into the HTML of every page, so the frontend reads it instantly. Only the
  numbers are sent; admin descriptions are not.
- **Defaults always exist.** Every slug has a default in
  `config/coefficients.php`. A DB row with the same slug overrides it. If a
  row is missing or deleted, the site keeps working with the default.
- **Edits apply on the next page load.** Saving or deleting through the admin
  page clears the cache right away. If you change a row directly in
  phpMyAdmin, the change shows up within **10 minutes** (the cache lifetime).

---

## All coefficients

### Climbing wall calculator (`shop.climbing.ge/.../climbing_wall_colculator`)

| Slug | Default | Meaning |
|---|---|---|
| `wall_square_price` | 120 | Wall surface price, $ per m² |
| `wall_discipline_bouldering` | 0.85 | Price multiplier for bouldering |
| `wall_discipline_sport_climbing` | 1.0 | Price multiplier for sport climbing |
| `wall_structure_indoor` | 1.0 | Price multiplier, indoor wall |
| `wall_structure_outdoor` | 1.15 | Price multiplier, outdoor wall |
| `wall_structure_standfree_indoor` | 1.1 | Price multiplier, stand-free indoor |
| `wall_structure_standfree_outdoor` | 1.3 | Price multiplier, stand-free outdoor |
| `wall_mat_price` | 80 | Safety mat, $ per m² (per m³ for bouldering mats) |
| `wall_protection_anchor_price` | 50 | $ per bolted anchor (sport climbing) |
| `wall_protection_rope_price` | 10 | $ per meter of belay rope |
| `wall_hold_price` | 10 | $ per climbing hold |
| `wall_foundation_price` | 300 | Foundation, $ per linear meter of wall width (stand-free only) |
| `wall_foundation_depth_ratio` | 0.12 | Foundation depth as a fraction of wall height (not a price; used in the PDF drawing) |
| `wall_roof_price` | 15 | Roof, $ per m² (outdoor walls) |
| `wall_vat_percent` | 18 | VAT, % |

The calculator's hint texts show the live values too: "Include VAT (18%)",
"$50 each" per anchor, "$10/m" of rope, "$10 each" per hold. The admin-only
PDF export (`WallCalculatorExportController`) uses the same foundation ratio
and VAT.

### Shop delivery period (business days)

| Slug | Default | Meaning |
|---|---|---|
| `delivery_online_order_min_days` | 2 | Normal (in-stock) order, minimum |
| `delivery_online_order_max_days` | 4 | Normal (in-stock) order, maximum |
| `delivery_produced_by_order_min_days` | 5 | Made-to-order product, minimum |
| `delivery_produced_by_order_max_days` | 9 | Made-to-order product, maximum |

Shown as "min-max" (e.g. "2-4") on:
- the product page delivery badge
- the checkout review step (`orderDeclorationPageComponent`)
- the customer's and the admin's order detail modals
- the admin product add/edit sale-type dropdown and the products list filter
- `GET /api/get_order/get_user_purchules` (`delivery_days` field, My Purchases table)

An order or cart gets the made-to-order period if **any** item has
`sale_type = produced_by_order`.

### Climber profile points

| Slug | Default | Meaning |
|---|---|---|
| `points_route_review` | 5 (env `POINTS_ROUTE_REVIEW`) | Points per route review |
| `points_mtp_review` | 5 (env `POINTS_MTP_REVIEW`) | Points per MTP review |
| `points_ascent` | 10 (env `POINTS_ASCENT`) | Points per summit ascent |
| `points_comment` | 2 (env `POINTS_COMMENT`) | Points per article comment |

Points are recalculated on every request (see
[CLIMBER_PROFILE.md](CLIMBER_PROFILE.md#the-points-system)), so changing a
weight immediately changes every climber's total and the "top active"
ordering. Points weights are whole numbers; any decimals are dropped.

---

## Using the admin page

Left menu → **Site Options → Coefficients**. Only visible to users with
`coefficient › show`.

| Column | Notes |
|---|---|
| ID | Row id |
| Slug | The key the code looks the value up by |
| Value | The number in use |
| Description | The admin-entered description. If it's empty and the slug is a known one, a built-in EN/KA description is shown instead |
| Edit / Delete | Need `coefficient › edit` / `coefficient › del` |

**Add / Edit modal** fields:
- **Slug** — required, lowercase letters, digits and `_` only, unique.
- **Value** — required, any number (decimals allowed, negatives allowed).
- **Description** — optional note, up to 1000 characters. Only admins see it.

> ⚠️ **Don't rename a slug** unless you know the code uses the new name. The
> code finds values by slug, so a renamed row is simply ignored and the
> default from `config/coefficients.php` is used instead. The same happens if
> you **delete** a row: the site goes back to the default value, and nothing
> breaks.

Adding a row with a slug the code doesn't use does nothing until a developer
reads it in code (see [Adding a new coefficient](#adding-a-new-coefficient)).

### Roles

The `admin` role has all four actions. To let another user or role edit
coefficients, grant `coefficient › show` + `edit` (and `add`/`del` if needed)
on the **Users & Permissions** page.

---

## Database

`coefficients`

| Column | Type | Notes |
|---|---|---|
| `id` | bigint, auto-increment | |
| `slug` | varchar(100), unique | |
| `value` | decimal(14,4) | Returned as a float by the API |
| `description` | text, nullable | Admin-only note |
| `created_at` / `updated_at` | timestamps | |

Migrations:
- `2026_09_26_215936_create_coefficients_table.php`
- `2026_09_26_215937_sync_coefficient_permission_with_admin_role.php` — adds the 4
  `coefficient` permissions and grants them to `admin`. Safe to run more than once.
- `2026_09_26_221641_add_description_to_coefficients_table.php`

Model: `App\Models\Coefficient`. Its `saved`/`deleted` hooks clear the cache.

---

## API

`routes/api/admin/set_user_routes.php`, controller
`Api\User\Admin\User\CoefficientController`. All routes need `auth:sanctum` +
`banned` and check the permission shown.

| Method | Path | Permission | Notes |
|---|---|---|---|
| GET | `/api/set_coefficient/get_all` | `show` | All rows, ordered by slug |
| GET | `/api/set_coefficient/get_coefficient/{id}` | `show` | 404 if missing |
| POST | `/api/set_coefficient/create` | `add` | Body: `slug`, `value`, `description?` → 201 |
| POST | `/api/set_coefficient/update/{id}` | `edit` | Same body → 200 |
| DELETE | `/api/set_coefficient/del/{id}` | `del` | 404 if missing |

Validation errors return `422 { message, errors: { field: [...] } }`.

There is intentionally **no public API**. Pages get the values from the HTML.

---

## Reading coefficients in code

**PHP**

```php
use App\Services\CoefficientService;

CoefficientService::get('wall_vat_percent');        // 18.0
CoefficientService::get('some_slug', 1.5);          // with a fallback
CoefficientService::deliveryDays($hasProducedByOrder); // "2-4" or "5-9"
CoefficientService::all();                          // full slug => value map
```

**JavaScript** (`resources/js/services/coefficients.js`)

```js
import { coef, deliveryDays } from '../../services/coefficients.js' // relative to your component

coef('wall_vat_percent', 18)   // 18 — the second argument is a fallback
deliveryDays(false)            // "2-4"
deliveryDays(true)             // "5-9"
```

To use one in a template, expose it in the component (`methods: { deliveryDays }`)
or put it in a computed property. i18n strings take the number as a parameter
(e.g. `$t('shop.product.delivery_online_order', { days: deliveryDays(false) })`),
so never write the number itself into a translation.

The wall calculator reads everything through `coepicients_mixin.js`, which
keeps its old `coepicients.<key>.coepicient` shape but now fills it from `coef()`.

---

## Adding a new coefficient

1. Add the slug and its default to `config/coefficients.php`.
2. Read it in code with `CoefficientService::get()` (PHP) or `coef()` (JS).
3. Add a description in both `resources/lang/i18n/en.json` and `ka.json` under
   `admin.coefficients.descriptions.<slug>`.
4. Optional: give the admins an `INSERT` (or let them add it through the admin
   page) so the row appears in the table. Until then the default applies.

If production runs `php artisan config:cache`, run it again after changing
`config/coefficients.php`.

---

## Deployment

1. `php artisan migrate` — creates the table and adds the permissions.
2. Insert the initial rows in phpMyAdmin (safe to run more than once):

```sql
INSERT INTO `coefficients` (`slug`, `value`, `created_at`, `updated_at`) VALUES
('wall_square_price', 120, NOW(), NOW()),
('wall_discipline_bouldering', 0.85, NOW(), NOW()),
('wall_discipline_sport_climbing', 1, NOW(), NOW()),
('wall_structure_indoor', 1, NOW(), NOW()),
('wall_structure_outdoor', 1.15, NOW(), NOW()),
('wall_structure_standfree_indoor', 1.1, NOW(), NOW()),
('wall_structure_standfree_outdoor', 1.3, NOW(), NOW()),
('wall_mat_price', 80, NOW(), NOW()),
('wall_protection_anchor_price', 50, NOW(), NOW()),
('wall_protection_rope_price', 10, NOW(), NOW()),
('wall_hold_price', 10, NOW(), NOW()),
('wall_foundation_price', 300, NOW(), NOW()),
('wall_foundation_depth_ratio', 0.12, NOW(), NOW()),
('wall_roof_price', 15, NOW(), NOW()),
('wall_vat_percent', 18, NOW(), NOW()),
('delivery_online_order_min_days', 2, NOW(), NOW()),
('delivery_online_order_max_days', 4, NOW(), NOW()),
('delivery_produced_by_order_min_days', 5, NOW(), NOW()),
('delivery_produced_by_order_max_days', 9, NOW(), NOW()),
('points_route_review', 5, NOW(), NOW()),
('points_mtp_review', 5, NOW(), NOW()),
('points_ascent', 10, NOW(), NOW()),
('points_comment', 2, NOW(), NOW())
ON DUPLICATE KEY UPDATE `slug` = `slug`;
```

   Descriptions can be typed in the admin page, or filled for all rows at once
   with `UPDATE … WHERE description IS NULL` statements (one per slug).

3. Build the frontend (`npm run build`, or let `webpack --watch` pick it up).
4. If config is cached on the server: `php artisan config:clear`.

---

## Troubleshooting

| Symptom | Cause / fix |
|---|---|
| Admin saved a value but the site still shows the old one | Reload the page; the values are loaded with the page, not live. If it's still old, see the next row. |
| Old value persists for up to 10 minutes after an admin edit; `laravel.log` has "could not clear the coefficients cache" | The cache file in `storage/framework/cache` belongs to a different OS user than the web server, so the server can't delete it. This happens after running `php artisan`/`tinker` as another user. Fix the ownership (`chown -R www-data storage/framework/cache`) or run artisan as the web user. |
| Changed a row in phpMyAdmin, no effect | Expected for up to 10 minutes (cache lifetime), or run `php artisan cache:clear`. |
| "Coefficients" is missing from the left menu | The user has no `coefficient › show` permission, or the permission migration hasn't run. |
| A value shows the default even though the table has a row | Check the slug spelling. It must match exactly, e.g. `wall_vat_percent`. |
| `window.__COEFFICIENTS__` is undefined in the browser | The page layout is missing `@include('partials.coefficients')`. The JS then falls back to the hardcoded fallbacks passed to `coef()`. |

# Tour price calculator

Each tour can have a flexible price list. On the tour page, the reservation form uses it as a calculator: the visitor picks the **duration** and the **group size** and sees the price live. The server recalculates the price and saves it with the reservation, and it appears in the admin table, the details modal and every reservation mail.

**Code:** `app/Services/TourPriceService.php` (rules, quote, save), `app/Models/Shop/Tour_price.php`, `resources/js/services/shop/tourPrice.js` (same rule in JS for the live preview), admin `user/pages/tour/forms/TourPricesEditorComponent.vue` (Edit tour → **Prices** tab), shop `shop/items/reservation_forms/TourMessageFormComponent.vue`.

## Price rules (`tour_prices`)

| Column | Meaning |
|---|---|
| `tour_id` | tour (cascade delete) |
| `days` | tour duration in days (1–60) |
| `persons_min` / `persons_max` | group size the rule is for; `persons_max` NULL = "and more" |
| `price` | decimal |
| `price_type` | `per_person` (price × persons) or `per_group` (one price for the whole group) |

**Prices are calculated in euro.** `tours.price_currency` defaults to EUR (migration `2026_11_10_210000_tour_price_currency_default_eur.php`), and `TourPriceService::CURRENCIES = ['EUR']`, so any other value is read as EUR. The Prices tab shows "Prices are in euro (€)" instead of a currency select. The column stays, so another currency could come back later. Existing tours are switched by the migration `2026_11_10_211000_switch_existing_tours_to_eur.php`. It is idempotent, so you don't need to run any manual SQL.

The currency applies to all rules of the tour. It is **not** in `Tour::$fillable`, because the tour form saves its whole loaded row and would otherwise undo a currency change made in the Prices tab. `TourPriceService::save` sets it directly.

Example (EUR): 1 day, 1 person: 250 per person · 1 day, 2–3: 180 per person · 1 day, 4+: 600 per group · 2 days, 1+: 400 per person.

## Matching (`TourPriceService::quote` = `tourPrice.js quote_price`)

1. The rules with the same `days`.
2. `persons_min ≤ persons ≤ persons_max` (no max = no upper limit).
3. When several match, the narrowest range wins.
4. `per_person` → price × persons, `per_group` → price. The result also has `per_person` = total ÷ persons.
5. No match (a duration or group size without a rule) → **price on request** (`null`). The reservation is still allowed.

Saving checks that `persons to ≥ persons from` and that two rules with the same `days` don't overlap. Both the editor (live, blocks Save) and the server (422) do this check.

## Admin: Prices tab

Edit tour → **Prices** (4th tab), saved separately from the tour with **Save prices**, which replaces all of the tour's rules.

- **Currency:** fixed to euro (badge).
- **Rules table:** one row per rule with days, persons from, persons to (empty = "and more"), price, and "price is" (per person / per group). Each row has an example ("2 persons → 360 €"), plus duplicate and delete buttons. A row with a problem turns yellow, the first problem shows above Save, and Save is disabled until it's fixed.
- **Add group size:** a new row for the same duration, starting after the largest group size so far.
- **Add duration:** copies the first duration's group sizes for one more day, with empty prices.
- **"What visitors see":** a preview table of group size (1–10) × duration with totals and per-person prices.
- **Add Tour page:** shows a hint that prices are set after saving.

Endpoints: `GET set_tour/get_tour_prices/{tour_id}` (`tour.show`), `POST set_tour/save_tour_prices/{tour_id}` `{currency, rules: [...]}` (`tour.edit`). Validation: days 1–60, persons 1–500, price ≥ 0, max 200 rules.

## Shop: calculator in the reservation form

`GET get_tour/get_tour_prices/{tour_id}` → `{currency, symbol, durations, rules}`.

The form is titled **"Calculate the price and reserve your tour"** and has two numbered steps:
- **Step 1, Calculate the price:** duration, persons, check-in and the price card. Its number turns into a green ✓ once all three are filled.
- **Step 2, Your details:** contact fields and the message. A logged-in user sees their profile box instead of the contact fields.

At the bottom, a summary line ("1 day · 3 persons · 540 €") and the email-confirmation note sit next to the button. The button reads **Reserve · 540 €**, or **Reserve the tour** when there's no price. i18n: `shop.tour.reserve.*`.

- **Tour duration:** chips for the durations the tour's [schedule](tour_schedule.md) allows (fixed list or range; otherwise the price list's durations, or 1–7 days). A tour with only one duration has it selected already. Required.
- **Number of persons:** a − / + stepper with a typed value (1–100).
- **Check-in:** the season calendar (see [tour_schedule.md](tour_schedule.md)). Only allowed start days can be picked, and the whole trip is highlighted.
- **Price card** (right side, sticky; below the inputs on phones):
  - the live total and a breakdown ("180 € × 3 persons · 1 day", or "Group price for 7 persons, 1 day"), plus "X per person" for groups
  - before choosing: "from X / person" (the cheapest per-person price for groups of 1–10)
  - no matching rule: "Price on request"
  - tour without rules: "The guide will tell you the price"
  - always the note "The final price is agreed with the guide. Nothing is paid now."
- **See all prices:** a group × duration table. Clicking a cell selects that duration and group size, and the current choice is outlined.
- The "check your email" panel after sending shows the saved duration · persons · price, as returned by the server.

## Saved with the reservation (`tour_reservations`)

| Column | |
|---|---|
| `days` | chosen duration (required, `form_data.days`) |
| `price_total` | server total, NULL = on request |
| `price_currency` | the tour's currency at that moment |
| `price_details` | JSON snapshot of the quote: `rule_id, days, persons, persons_min, persons_max, price_type, unit_price, total, per_person, currency, symbol` |

A later price change doesn't touch existing reservations. Admin-created reservations go through the same calculation.

Shown in:
- the admin table (Days and Price columns)
- the details modal (Duration tile with the tour's own length text, and a Price tile with the breakdown, or "on request" plus the tour's "from" price)
- the shop confirmation page (duration and price)
- all four reservation mails (Duration and Price rows)

Mails write the currency code ("540 EUR") instead of a symbol, so every mail client shows it.

## Migration

`2026_11_10_170000_create_tour_prices_and_reservation_price_fields.php`: the `tour_prices` table, `tours.price_currency`, and the reservation `days / price_total / price_currency / price_details` columns.

## i18n

`shop.tour.calc.*` (calculator), `shop.tour.message.err_days`, `admin.tour_prices.*` (Prices tab, table columns, modal tiles): en + ka.

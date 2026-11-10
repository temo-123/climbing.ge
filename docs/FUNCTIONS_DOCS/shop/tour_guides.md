# Tour guides: general guide & guide in charge

Every tour has up to 4 guides. One of them is the **general guide**, who gets new reservations. Every reservation has one **guide in charge**, always one of that tour's guides. That guide can be switched, a whole tour can be handed over, and every change is mailed and logged.

**Code:**
- `app/Services/TourGuideService.php`: all rules.
- Models: `User_tour` (`is_main`), `Tour_reservation` (`guide_id`, `guide()`), `Tour_reservation_guide_change`.
- Admin modal: `user/items/modal/tab_modals/TourGuideRelationModalComponent.vue` (tours list → guides button).
- Details modal card: `TourReservationDetailsModal.vue`.

## Data

| Where | What |
|---|---|
| `user_tours.is_main` | the tour's general guide (one per tour) |
| `tour_reservations.guide_id` | guide in charge (FK users, `nullOnDelete`) |
| `tour_reservation_guide_changes` | history: `reservation_id, from_user_id, to_user_id, changed_by, reason (manual / handover / guide_removed), note` |

Migration `2026_11_11_100000_add_tour_guide_roles_and_reservation_guide.php` backfills existing data: the first guide added (lowest `user_tours.id`) of every tour becomes general, and every reservation gets its tour's general guide.

## Rules

- **First guide = general.** `add_guide` sets `is_main` when the tour had no guide, and then also gives reservations that had nobody in charge to that guide. A new tour's creator is its first guide.
- **New reservation** → `guide_id` = the general guide (`TourReservationService::create`).
- **Switch** (`assign`) only to a guide of the reservation's tour, otherwise 422 "This user is not a guide of this tour". It is logged, and mails go out (see below). Switching to the same guide does nothing.
- **Make general** (`set_main`): only the flag changes; reservations stay with their guides.
- **Hand over** (`handover`): a guide becomes general, and optionally every *open* reservation of the previous general guide moves to them (`reason = handover`). The guests can optionally be notified.
- **Remove a guide** (`remove_guide`):
  - if they were general, the next guide (by add order) becomes general
  - their open reservations move to the (new) general guide, with mails (`reason = guide_removed`)
  - past reservations point to the new general guide without mails
  - with no guide left, `guide_id` becomes NULL
- **Open** = check-in today or later, or no date.
- Max 4 guides per tour.

## Mails

| When | To | Template |
|---|---|---|
| Reservation confirmed by the guest | **guide in charge** + info@ (sites.email). All tour guides only when nobody is in charge. | `emails/tour/reservation_confirmed_guide`. The trip card shows "🧭 Guide" (name, email, phone). |
| New reservation (admin notice) | info@ | `emails/tour/admin_new_reservation`, also with the 🧭 Guide row |
| Switch / handover / guide removed | the new guide | `guide_assigned`: "🧭 A reservation is now yours". Previous guide, who changed it, an optional note, Call / Email buttons when confirmed, the trip card, dashboard link. |
| | the previous guide | `guide_unassigned`: "Reservation #N moved to another guide", with the new guide's name and email for a hand-over |
| | the guest (only confirmed, optional) | `guide_changed_client`: "Meet your new guide", with name, phone, email; "nothing else changes" |
| Reservation confirmed | the guest | `reservation_confirmed_client`: "Your guide" = the guide in charge |

A failing mail is logged (`Tour guide switch mail failed`) and never blocks the switch.

## Admin: "Manage tour & guides" modal

Tours list → the guides column button. The list shows the general guide's name in its "General guide" column.

- **Guides:**
  - avatar, name, email / phone
  - a **★ General guide** badge
  - "N open reservation(s) · M in total"
  - **Make general** and remove buttons
  - add a guide with a name / #id search box, plus hints for the first guide and helpers
- **Hand over the tour** (with 2+ guides): choose a guide, "Also move <general>'s N open reservations", "Tell the guests about their new guide".
- **Open reservations:** a table with a guide select per row, which switches immediately. "Email the guest about the new guide" applies to every switch.
- **Permissions:**
  - editing guides needs `tour › edit`
  - the reservations list needs `tour_reservation › show`
  - switching needs `tour_reservation › edit`
  - removing a guide asks for confirmation (and warns that the general guide's reservations will move)

## Admin: reservations

- **Table:** a "Guide in charge" column (`guide_name` from `get_all_reservations` / `get_declarations`).
- **Details modal:**
  - a **Guide in charge** card with name, ★ when general, and email / phone
  - a "Switch to another guide of this tour" select (only that tour's guides) with an optional note for the new guide
  - "Email the guest" (only offered for confirmed reservations)
  - the history, e.g. "Temo → Manana · 10 Nov 2026, 13:26 · switched · by Temo · “note”"
  - the Tour card lists the tour's guides with ★
- **Dashboard:**
  - a **By guide (open trips)** panel with trips, confirmed and persons per guide in charge ("Nobody in charge" in orange)
  - calendar tooltips show 🧭 guide
- **"My tours reservations"** (`get_user_reservations`): reservations of the tours I guide, with a Guide column and `is_mine`. Rows assigned to me are accented; unconfirmed rows are yellow.

## Endpoints

| Method | Path | Permission |
|---|---|---|
| GET | `set_tour/get_tour_users/{tour_id}` | `tour › show`: guides with `is_main`, contact, open / all reservation counts |
| POST | `set_tour/add_tour_user/{tour_id}` `{user_id}` | `tour › edit` |
| DELETE | `set_tour/remove_tour_user/{tour_id}/{user_id}` | `tour › edit`, returns `moved` |
| POST | `set_tour/set_main_guide/{tour_id}` `{user_id}` | `tour › edit` |
| POST | `set_tour/handover_tour/{tour_id}` `{user_id, move_reservations, notify_clients}` | `tour › edit`, returns `moved` |
| GET | `set_tour/get_tour_open_reservations/{tour_id}` | `tour_reservation › show` |
| POST | `set_tour/set_reservation/assign_guide/{reservation_id}` `{user_id, note?, notify_client?}` | `tour_reservation › edit`, returns fresh details |

## Fee & completion

Migration `2026_11_11_120000_add_language_completion_commission_to_tour_reservations.php`.

| Column | Meaning |
|---|---|
| `commission_percent` / `commission_amount` | climbing.ge fee snapshot: **15 %** (`TourGuideService::COMMISSION_PERCENT`) of `price_total` when the guide in charge is **not** the tour's general guide. Amount NULL when the price is "on request" |
| `commission_paid_at` | the guide paid the fee |
| `completed_at` | the tour took place. Used for **every** reservation, the general guide's too, which simply have no fee |

- **When the fee is set** (`apply_commission`, on every switch):
  - set when the new guide isn't the general guide
  - cleared when the reservation is (back) with the general guide
  - a handover or a removed guide moves reservations to the general guide, so no fee
  - a **paid** fee is never changed
  - later "Make general" doesn't touch existing snapshots
- **Marking** (`POST set_tour/set_reservation/set_status/{id}` `{completed?, commission_paid?}`, `tour_reservation › edit`, each can be undone): "paid" is refused (422) when the reservation has no fee.
- **Where:**
  - **Details modal:** a "Completion & fee" card with Mark completed / Mark fee paid and the rule text.
  - **Manage tour & guides modal:** a **To settle** list with confirmed past trips not marked completed and unpaid fees, each with buttons.
  - **Reservations table:** Fee ("81 € · unpaid / paid ✓") and Completed columns. Row colours: red = fee unpaid, green = completed, grey = ended but not marked.
  - **Dashboard:** a **Fees unpaid** card (sum, count, trips to mark completed).
  - **Guide's own page:** a Fee column.
- **Mails:** staff mails (`guide_assigned`, `reservation_confirmed_guide`, `admin_new_reservation`) show an amber **climbing.ge fee · 15%** box ("81 EUR", ✓ paid, "please pay it after the tour"). `guide_assigned` adds a one-line explanation. **Guest mails never show the fee.**

## Tour language

`tour_reservations.tour_language`: `ka` | `ru` | `en`, **required** in the reservation form (`form_data.tour_language`, `in:ka,ru,en`).
- **Form:** chips ქართული / English / Русский in step 1.
- **Shown in:** the confirmation page; every reservation mail (a "🗣 Language" row in the trip card, guest and staff); the admin table column; a details modal tile; the Manage tour modal's open reservations; calendar tooltips; the guide's own page.
- Labels: `TourReservationService::LANGUAGES` (mails, English names) and `shop.tour.lang.*` (UI).

## Privacy fix along the way

The public `get_tour/get_all_tours` and `get_tour/{lang}/{url_title}` used to send every guide's **whole** user row (email, phone, address…) to anyone. They now send only `id, name, surname, image`, general guide first. `get_tour/get_guide/{id}` (the public guide profile) returns the same public fields as team members: `name, surname, image, member_status, city, country, my_bio, email, sites`.

## Toasts

The tour admin components (details modal, Prices tab, Season & duration tab) used `this.$notify`, which the user panel doesn't have, so their success and error messages never showed. They now use the app toast: `this.$bus.$emit('toast', { type, message })`.

## i18n

`admin.tour_guides.*`, `admin.tour_fee.*`, `shop.tour.lang.*`, `shop.tour.message.err_language`, `admin.tour_dashboard.by_guide / trips`: en + ka.

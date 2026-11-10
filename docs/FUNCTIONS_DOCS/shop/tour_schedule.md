# Tour season & duration schedule

When a tour can be booked and for how long. For example, ice climbing only runs 1 Dec – 15 Mar. The reservation calendar lets visitors pick only start days that work, and the server checks the trip again when it's saved.

**Code:**
- `app/Services/TourScheduleService.php`: rules, check, save.
- `app/Models/Shop/Tour_season.php`.
- `resources/js/services/shop/tourSchedule.js`: the same rules in JS.
- Calendar: `shop/items/reservation_forms/TourDatePickerComponent.vue`.
- Admin: `user/pages/tour/forms/TourScheduleEditorComponent.vue` (Edit tour → **Season & duration** tab).

## Data

`tours`:

| Column | Meaning |
|---|---|
| `all_year` | 1 = any time of the year (default), 0 = only inside `tour_seasons` |
| `duration_mode` | NULL = durations from the price list (or 1–7 days without prices), `fixed` = `duration_days`, `range` = `duration_min` … `duration_max` |
| `duration_days` | JSON list for `fixed`, e.g. `[1,2,3,5]` |
| `duration_min` / `duration_max` | for `range` |
| `start_weekdays` | JSON list of allowed start weekdays, 0 = Sunday … 6 = Saturday; NULL = any day |
| `booking_lead_days` | book at least N days ahead (0 = same day is fine) |

None of these are in `Tour::$fillable`, for the same reason as `price_currency`: the tour form saves its whole loaded row. `TourScheduleService::save` sets them.

`tour_seasons` holds yearly periods `from_month/from_day → to_month/to_day` that repeat every year. When `from` is after `to`, the period crosses the new year (1 Dec → 15 Mar). Up to 12 periods per tour, and 29 Feb is allowed.

Migration `2026_11_10_190000_add_tour_schedule_and_seasons.php`.

## Rules (`check_trip` = `start_problem` in JS)

A start day is allowed when:
1. it is not before today + `booking_lead_days`,
2. its weekday is in `start_weekdays` (when set),
3. **every** day of the trip (start … start + days − 1) is inside a season (when not all year).

The duration must also be one of `durations()`. If a tour is set to "periods" but has none, nothing is blocked.

Server errors come back as 422 with `errors['form_data.check_in' | 'form_data.days']` and show under the field in the form:
- "This duration is not available for this tour."
- "This tour must be booked at least N day(s) ahead."
- "The tour does not start on this day of the week."
- "The tour does not run on this date (out of season)."
- "The trip would end out of season…"

## Shop: calendar in the reservation form

It replaces the browser's `datetime-local` field, which can't block arbitrary dates. **Date only, no time:** `v-model` = `YYYY-MM-DD`. Older reservations may still have `…THH:mm`; the modal and the confirmation page show a time only when there is one, and parse date-only values as local dates (not UTC midnight).

- **Header:** "Season: 1 Dec – 15 Mar · starts on Saturday · book 3 day(s) ahead", or "Runs all year".
- **Months:** two on desktop and one on phones, Monday first, with ‹ › to move (from this month up to 2 years ahead). It opens on the first month that has a free day, so a winter tour viewed in summer opens in December.
- **Day states:**
  - free days are tinted
  - unavailable days are grey (crossed out when out of season)
  - today has a ring
  - the chosen start is solid, and the rest of the trip is highlighted up to the end day
- **Clicking a grey day** shows why: past, lead time, weekday, out of season, or "would end out of season".
- **Foot:** "Sat, 12 Dec 2026 → Mon, 14 Dec 2026", or the reason when a grey day was clicked.
- **Duration change:** if a new duration (or schedule) makes the chosen day impossible, the date is cleared with a note.
- **Duration chips** come from the schedule's `durations`.

Data: the `schedule` key of `GET get_tour/get_tour_prices/{tour_id}` → `{all_year, seasons, durations, start_weekdays, lead_days}`.

## Admin: Season & duration tab

Edit tour → **Season & duration** (5th tab). It is saved separately with **Save season & duration**.

- **Season:**
  - an **All year / Only in periods** switch
  - period rows: from day + month → to day + month, with an "over new year" tag and delete
  - Add period
  - a **12-month bar** showing which months the tour runs in
- **Duration options:** three cards: **From the price list** (shows which durations have prices), **Fixed options** (1–21 day chips), or **Any length in a range** (from–to). If an offered duration has no price row, a note says it will show "price on request".
- **Start days:** Mon–Sun toggles (at least one), and "Book at least N day(s) ahead".

The duration cards are buttons, not radio + label, because the edit page's `.tabs` CSS restyles every radio label.

Endpoints: `GET set_tour/get_tour_schedule/{tour_id}` (`tour.show`, includes `price_durations`), `POST set_tour/save_tour_schedule/{tour_id}` (`tour.edit`).

## i18n

`shop.tour.schedule.*` (calendar, reasons `why_*`), `admin.tour_schedule.*` (tab): en + ka.

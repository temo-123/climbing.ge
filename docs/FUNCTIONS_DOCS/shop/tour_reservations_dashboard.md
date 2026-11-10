# Tour reservations dashboard

The admin page `/all_tours_reservations_list` is the tour reservations dashboard. Stat cards, a calendar of reserved trips, a month chart and top tours sit **above** the existing reservations table. The table, its tabs, the row colours, the calendar modal and the details modal are unchanged.

In the menu it lives in the **Shop dashboard** group, under "Shop dashboard" (it used to be in the Shop group), with the `tour_reservation.show` permission. The shop dashboard page (`/shop_dashboard_page`) also gets a one-line **Tour reservations** card with upcoming / waiting / new-in-7-days counts that links here. It is only shown with `tour_reservation.show`.

**Code:**
- `user/pages/tour/list/for_admin/ReservationsDashboardComponent.vue` (dashboard)
- `AllToursReservationsPage.vue` (page)
- `dashboards/shop/assets/TourReservationsSummaryComponent.vue` (shop dashboard card)
- `TourReservationService::dashboard_stats()`
- `GET set_tour/get_reservation/get_reservation_stats` (`tour_reservation.show`)

## Stat cards

| Card | Number |
|---|---|
| Upcoming trips | reservations whose trip ends today or later (check-in + days − 1), plus "N persons" |
| Waiting for confirmation | email not confirmed yet (`verificate` 0 / NULL) |
| Confirmed | confirmed, plus "X% of all reservations" |
| Expected (confirmed) | sum of `price_total` of confirmed upcoming trips, plus "this year: …" (by check-in year). One line per currency (EUR today) |
| New in 7 days | created in the last 7 days, plus the total count |

Reservations without a check-in date (the oldest rows) only count in the totals, not in upcoming / past / months / calendar.

## Calendar

- A month view, Monday first, with ‹ › and **Today**.
- Each reservation is drawn on every day of its trip:
  - the first day (and each Monday) shows "Name · persons"
  - the following days continue the bar
  - green = confirmed, yellow = waiting
  - at most 3 per day, then "+N"
  - hovering shows name, tour, persons, "day n of total" and status
- **Clicking a trip opens the reservation details modal** (the same one as the table's eye button).
- The calendar data covers trips ending 3 months back up to everything ahead.
- On phones the bars become thin coloured lines.

## Side panels

- **Reservations per month:** a Google ColumnChart, stacked confirmed (green) + waiting (yellow), by check-in month, from 6 months back to 6 months ahead.
- **Top tours:** the 5 tours with the most reservations, with a bar, the count and how many are confirmed.

Verifying or deleting in the details modal reloads both the table and the dashboard (`refresh_all`).

## i18n

`admin.tour_dashboard.*`: en + ka.

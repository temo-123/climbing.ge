# Shop dashboard: order & revenue charts

User panel → Shop dashboard (`pages/dashboards/shop/ShopDashboardPageComponent.vue`) → `items/dashboards/OrderDiogramComponent.vue`. Data: `GET get_order/get_order_dashboard/{period}` (`order › show`) → `app/Services/OrderStatisticsService.php`.

## Design concept

The charts must agree with the order screens and never draw what didn't happen.
- **Zero-filled buckets:** a quiet day or month is a 0 column, not a gap the line jumps over.
- **Revenue** = the same order total the modals and invoice show (`OrderService`: products − discount + shipping), not raw line sums.
- **Only real orders count:** confirmed, not `payment_failed`, not cancelled. Orders waiting for e-mail confirmation or online payment, and failed / cancelled ones, are reported as separate counts under the tiles, not drawn.

## Periods & buckets

| Period | Bucket |
|---|---|
| `today` | hour |
| `7days`, `30days` (and legacy `1month`) | day |
| `3months`, `6months` | week (keys are Mondays) |
| `1year`, `2years`, `3years`, `all` | month (`all` starts at the first order) |

## Response

`{ period, granularity, from, to, currency, buckets: [{key, from, website, manual, orders, revenue, items}], summary: {orders, website, manual, revenue, items, avg_order, awaiting, failed, cancelled, previous: {from, orders, revenue} | null} }`. `previous` is the same-length window right before (null for `all`).

`get_order_statistics/{period}` and `get_order_finance_statistics/{period}` still return the old table shape (`[['Date','Orders'], [key, n], …]`), now built from the same service.

## UI

- Period buttons in one row, plus refresh.
- Stat tiles: Orders (website · manual, change vs previous period), Revenue (change), Average order, Items sold.
- A note on what's counted and what was left out.
- Two charts, never one dual-axis chart:
  - **Orders:** stacked columns, Website (blue) / Manual (orange), with a legend.
  - **Revenue:** single series, no legend.
- Colors are the validated categorical pair (light `#2a78d6`/`#eb6834`, dark `#3987e5`/`#d95926`).
- Hovering a period shows one tooltip with all its values (`focusTarget: 'category'`). X labels are thinned to about 8 and localized (`Intl.DateTimeFormat`, "Week of …").
- An empty-period state, and "Show as table" with exact values.
- The old `curveType: 'function'` smoothing was removed; it drew the line below 0.

## Admin home / dashboard shop tiles

`DashboardOverviewController::get_admin_overview` → `OrderStatisticsService::overview('7days')`:
- `orders_week` and `revenue_week`: same rules as the charts. `revenue_week` used to sum `orders.payment_amount`, which only online TBC payments fill, so every cash or manual order counted as 0.
- `awaiting_confirmation`: a new tile.
- `open_orders` (`open_orders_count()`): confirmed, not failed / cancelled, and not `Delivered` / `Order Complete`. It used to look only for `Treatment` / `Preparation for shipment` / `Ready to ship`, which missed `pending` and `treatment`.

The dashboard chart defaults to **7 days**; Today / 7 days / 30 days / 3 m / 6 m / 1 y / 2 y / All.

Tests: `tests/Feature/Orders/OrderStatisticsTest.php`.

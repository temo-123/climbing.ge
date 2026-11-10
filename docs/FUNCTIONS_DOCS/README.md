# Function docs

One page per function: flows, design concept, states and rules. Update a function's page whenever its behaviour or design changes, and add a page + a row here for new functions. Technical references per subdomain stay in `docs/*.md`.

> **Rebuilt October 2026.** This folder used to be git-ignored, and its files were lost locally after the `front` → `master` merge. Only the pages below could be restored. Rewrite the others (design language, site search, toasts & undo, image watermark, performance, motion & scenes, image viewer, local businesses, similar articles, preferences & welcome, climber profile, GPS tracks, shop catalog / product card / product page…) as you next touch each function, and add their rows here.

| Function | Page |
|---|---|
| Login & sign-up modal, login-gated actions | [global/login_and_signup.md](global/login_and_signup.md) |
| SEO: crawler HTML, breadcrumbs, search box, page locale | [global/seo_crawler_html.md](global/seo_crawler_html.md) |
| Indoor gym opening hours (every day / per weekday / status) | [guide/indoor_gym_hours.md](guide/indoor_gym_hours.md) |
| Climber points & active-climbers list | [guide/climber_points.md](guide/climber_points.md) |
| Mountaineering route page: mountain massif card | [guide/mount_massif_card.md](guide/mount_massif_card.md) |
| Article right menu: sections built from the page's subtitles, current-section highlight | [guide/article_right_menu.md](guide/article_right_menu.md) |
| Article pages: Text / Cards view switch | [guide/article_view_switch.md](guide/article_view_switch.md) |
| Outdoor / ice sectors: Full / Tiles / Table / Tabs views, whole-row route click | [guide/sector_views.md](guide/sector_views.md) |
| Admin users list & Manage-user modal | [user/admin_user_manage.md](user/admin_user_manage.md) |
| Admin user-info modal (read-only, all related data) | [user/admin_user_info.md](user/admin_user_info.md) |
| Admin orders: buyer info in list & details modal | [shop/admin_order_buyer_info.md](shop/admin_order_buyer_info.md) |
| Order details: price breakdown, delivery card, invoice PDF | [shop/order_details_invoice.md](shop/order_details_invoice.md) |
| Order placement: one service for checkout / manual / legacy orders, stock, buyer linking, test suite | [shop/order_placement.md](shop/order_placement.md) |
| Shop dashboard order & revenue charts | [shop/order_statistics.md](shop/order_statistics.md) |
| Tour reservation: email confirmation, admin notice, verified column & row colors | [shop/tour_reservation_verification.md](shop/tour_reservation_verification.md) |
| Tour reservations dashboard: stat cards, calendar of reserved trips, month chart, top tours above the table; shop dashboard card | [shop/tour_reservations_dashboard.md](shop/tour_reservations_dashboard.md) |
| Tour season & duration schedule: yearly periods, duration options, start weekdays, lead time, reservation calendar | [shop/tour_schedule.md](shop/tour_schedule.md) |
| Tour price calculator: price rules per duration × group size, live calculator in the form, price saved with the reservation | [shop/tour_price_calculator.md](shop/tour_price_calculator.md) |
| SEO dashboard: Search Console data + content quality list | [user/seo_dashboard.md](user/seo_dashboard.md) |
| SEO: URLs, 404s, canonicals, renamed slugs (301) | [global/seo_crawler_html.md](global/seo_crawler_html.md) |

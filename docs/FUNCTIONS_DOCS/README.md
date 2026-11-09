# Function docs

One page per function: flows, design concept, states and rules. Update a function's page whenever its behaviour or design changes, and add a page + a row here for new functions. Technical references per subdomain stay in `docs/*.md`.

> **Rebuilt October 2026.** This folder used to be git-ignored, and its files were lost locally after the `front` → `master` merge. Only the pages below could be restored. Rewrite the others (design language, site search, toasts & undo, image watermark, performance, motion & scenes, image viewer, local businesses, similar articles, preferences & welcome, climber profile, GPS tracks, shop catalog / product card / product page…) as you next touch each function, and add their rows here.

| Function | Page |
|---|---|
| Login & sign-up modal, login-gated actions | [global/login_and_signup.md](global/login_and_signup.md) |
| SEO: crawler HTML, breadcrumbs, search box, page locale | [global/seo_crawler_html.md](global/seo_crawler_html.md) |
| Indoor gym opening hours (every day / per weekday / status) | [guide/indoor_gym_hours.md](guide/indoor_gym_hours.md) |
| Climber points & active-climbers list | [guide/climber_points.md](guide/climber_points.md) |
| Admin users list & Manage-user modal | [user/admin_user_manage.md](user/admin_user_manage.md) |
| Admin user-info modal (read-only, all related data) | [user/admin_user_info.md](user/admin_user_info.md) |
| Admin orders: buyer info in list & details modal | [shop/admin_order_buyer_info.md](shop/admin_order_buyer_info.md) |
| Order details: price breakdown, delivery card, invoice PDF | [shop/order_details_invoice.md](shop/order_details_invoice.md) |
| Order placement: one service for checkout / manual / legacy orders, stock, buyer linking, test suite | [shop/order_placement.md](shop/order_placement.md) |
| Shop dashboard order & revenue charts | [shop/order_statistics.md](shop/order_statistics.md) |
| SEO dashboard: Search Console data + content quality list | [user/seo_dashboard.md](user/seo_dashboard.md) |
| SEO: URLs, 404s, canonicals, renamed slugs (301) | [global/seo_crawler_html.md](global/seo_crawler_html.md) |

# Guidebook dashboard

User panel → **Guidebook Dashboard** (`/guide_dashboard_page`, `guide_task › show`).

## Design concept

Answers three questions, in this order: *what needs me now?*, *how big is the guidebook and is it healthy?*, *what are donors and climbers doing?*. **Guidebook data only** — user statistics live on the Users list → [Dashboard tab](../user/users_dashboard.md); shop numbers are on the Shop dashboard.

## Page (`pages/dashboards/guide/GuideDashboardPageComponent.vue`)

- `cg-page-head cg-dashboard__hero cg-art-mountains`: eyebrow *Guidebook*, title, one-line lead.
- `cg-segmented` tabs with icons: **Overview · Donations · Community** (Community needs `comment › show`). The open tab is in `?tab=` (`donations`, `community`; none = overview), so reloads and links keep it. Next to the tabs, a one-line hint says what the tab is for. On phones the tabs show icons only (label in `title`).
- Donations (`assets/DonationListComponent.vue`) and Community (`assets/CommunityModerationComponent.vue`) sit in a `cg-panel`; their content is unchanged.

## Overview (`items/dashboards/SiteDataCountListComponent.vue`)

Data: `GET set_site_data/site_data_counts`. Every link goes through `can_open_route()`; a row the user can't open shows only the number.

1. **Needs attention** — `AdminAttentionComponent` with `['guide', 'moderation']` (unpublished articles, hazards, reports, complaints…).
2. Toolbar "The guidebook in numbers" + updated time + Refresh; integrity alert and admin notifications as before.
3. **Headline numbers** (4 tiles with a coloured edge): outdoor spots (+ regions), sectors, routes (single + multi-pitch, "incl. N multi-pitch"), articles (EN · KA).
4. **Routes by type** — horizontal bars (sport, boulder, top rope, trad, multi-pitch, aid) scaled to the largest, total pitches below.
5. **Translations** — EN and KA coverage of all articles as % bars (green 100 %, yellow ≥ 80 %, red below) with "N articles still need this language".
6. **Content by category** — list rows (outdoor, indoor gyms, ice, mountaineering routes, massifs, other activities, news, tech tips) → article lists.
7. **Events & competitions** — upcoming / past pairs. **Community** — a red line when comment complaints wait, then comments, gallery images, slider images, guidebook followers.
8. **Data health** — one `EntityLocaleErrorsComponent` for all content types (articles, mounts, products, services, tours, films, posts; one `set_database/table_stats` request instead of one per section). It emits `checked` with the issue count; 0 → green "All content is healthy".

i18n: `admin.guide_dash.*` (+ existing `admin.dashboards.*` labels).

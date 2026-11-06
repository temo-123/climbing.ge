# SEO dashboard (Search Console + content quality)

`user.climbing.ge/seo_dashboard`, under Site options → SEO dashboard. Permission `seo › show` to see it, `seo › edit` for "Sync now". Both are granted to the admin role by `2026_11_06_130100_sync_seo_permissions_with_admin_role.php`.

## What it shows

| Tab | What | Data |
|---|---|---|
| **Overview** | Clicks, impressions, CTR and average position for the period (7 / 28 / 90 days) against the previous period of the same length; clicks per day (chart, or table view); totals per service (host) and per language (`/ka` = Georgian) | `search_console_pages` |
| **Opportunities** | **Almost on page 1:** position 4.5–20 with impressions ≥ max(20, 2 × days). **Low click rate:** top 10, impressions ≥ 100, CTR under half the typical CTR for its position. **Losing traffic:** clicks ≤ 70% of the previous period, from ≥ 10. Each comes with a hint on what to do. | same |
| **All pages** | Every URL with its metrics, change in clicks and position, and flags; filter by service | same |
| **Content quality** | Published content with problems, most-seen in Google first (impressions, last 28 days), then by weight | content tables + traffic |

Every row expands to its search queries (last 28 days; Google hides rare ones) and links to the public page. It also links to the admin edit form when the user may open it (`can_open_route`).

### Content-quality checks (`SeoDashboardService::contentQuality()`, per language)

| Code | Rule | Weight |
|---|---|---|
| `missing_translation` | no locale row, or a title without a real word | 5 |
| `placeholder_summary` | short description has fewer than two real words (`.`, `-`, empty) | 3 |
| `thin_text` | text fields under the type's minimum (articles 100 words, events 60, posts 150, products / services / tours 50) | 3 |
| `not_georgian` | Georgian title + summary contain no Georgian letters | 2 |
| `no_image` | article / event / post without a main image | 2 |
| `short_summary` | summary under 70 characters | 1 |
| `caps_title` | title is all capitals (≥ 8 Latin letters) | 1 |
| `long_title` | title over 60 characters (Google cuts it) | 1 |

The list is cached for 10 minutes and the cache is cleared by every sync. It works without Search Console; it is then sorted by weight only.

## Data flow

- **Sync:** `php artisan app:sync-search-console` runs nightly at 05:30 (`Console\Kernel`) and does nothing when not configured.
  - It re-reads the last 5 days (Search Console data settles after 2–3 days). `--days=480` back-fills the 16 months Google keeps. `--check` tests the key and lists the properties it can read.
  - Admins can also "Sync now".
  - The last result (or error) is kept in cache under `search_console:last_sync`.
- **Client:** `App\Services\Seo\SearchConsoleClient` uses no SDK. It signs a service-account JWT (RS256, openssl), exchanges it for an OAuth token (cached 50 min), then calls `searchAnalytics.query`, paging through the 25 000-row limit. It reads only `dataState: final` data.
- **Tables** (migration `2026_11_06_130000_create_search_console_tables.php`):
  - `search_console_pages`: one row per day and URL (`url_hash` = sha1). `content_type`, `content_id` and `locale` come from `ContentResolver::resolve()`.
  - `search_console_queries`: a 28-day snapshot per URL and query, replaced on every sync.
- **Resolver:** `App\Services\Seo\ContentResolver` maps URL ⇄ record for every content type. Old slugs go through `url_redirects`, so traffic recorded before a rename still counts for the page. It also gives the title, public URL and admin edit route.
- **API** (`routes/api/admin/set_user_routes.php`, `SeoDashboardController`): `GET set_seo/status | overview | pages | page_queries/{url_hash} | content_quality`, `POST set_seo/sync`.
- **Front end:** `resources/js/components/user/pages/seo/SeoDashboardPage.vue` + `assets/` (`SeoTrendChart`, `SeoPagesTable`, `ContentQualityList`). Strings are under `admin.seo.*`.

## Setup (once)

1. In Google Cloud Console, create (or pick) a project and enable **Google Search Console API**.
2. Under IAM → Service accounts, create a service account (no roles needed). Under Keys → Add key → JSON, download the key.
3. In Search Console, add a **Domain property** for `climbing.ge` (DNS TXT record) if it doesn't exist yet. It covers every subdomain. Then go to Settings → Users and permissions → Add user: the service account's e-mail, permission **Restricted**.
4. On the server:
   - Upload the key as `storage/app/search-console.json` (git-ignored), or set `SEARCH_CONSOLE_CREDENTIALS=/path/key.json`.
   - Set `SEARCH_CONSOLE_PROPERTIES=sc-domain:climbing.ge`. It is comma-separated, so URL-prefix properties also work: `https://shop.climbing.ge/`.
   - Run `php artisan config:cache` if the config is cached.
5. Run `php artisan app:sync-search-console --check` to confirm the property is listed, then `php artisan app:sync-search-console --days=480` once.

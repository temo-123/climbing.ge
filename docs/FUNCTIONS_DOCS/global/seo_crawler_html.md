# SEO: crawler HTML, breadcrumbs, search box

Every public page (guidebook, shop, blog, summit, films; not user.climbing.ge) is a Vue SPA, so without JavaScript its `<body>` was empty. Bing, social previews and AI crawlers (GPTBot, ClaudeBot, Perplexity) mostly don't run JS, and Google indexes JS pages later and less reliably. The server now prints a plain-HTML version of the page as well.

## Flow

1. `SeoService::for{Site,Shop,Blog,Summit,Films}()` resolves the page as before. Each meta method calls `$this->page($h1, $sections, $links, $crumbs)` before `build()`.
2. `build()` adds two keys:
   - `body`: `['h1', 'html', 'links']`.
   - `breadcrumbs`: `['trail' => [[label, url]…], 'schema' => BreadcrumbList]`. It is `null` on a subdomain's home page.
3. `partials/seo_body.blade.php`, inside `#app` in each public layout, prints a breadcrumb `<nav>`, then the `<h1>`, the text, and the link list.
4. `partials/seo.blade.php` adds `class="js"` to `<html>` with an inline script, and `.js .cg-ssr{display:none}`. JS users never see the block, and `app.mount('#app')` replaces it anyway.

## What each page gets

| Page | h1 + text | Links | Trail parent |
|---|---|---|---|
| Guidebook article (outdoor, mountaineering, indoor, ice, other, news…) | title, short description, text, route / how to get there / best time / what you need / info (with `<h2>`s) | none | its list page |
| Guidebook list (`/outdoor`, `/mountaineering`, `/events`…) | section title + description | every published item (≤300) | none |
| Event | title, description, text, info | none | Events |
| Shop product / service / tour | title, description, text (+ material, or location / duration) | none | Products / Services / Tours |
| Shop list (`/products`, `/services`, `/tours`) | section title + description | every published item | none |
| Home pages | default title + description | site: sections + 30 outdoor + 30 mountaineering; shop: sections + all tours / services + 30 products; blog: 60 posts; films: 150 films; summit: all summits | none |
| Anything else | page title + meta description | none | none |

Link lists are cached for 10 minutes per host + locale (`seo_body:*`). Article text pages aren't cached, because they're one query and already loaded for the meta tags.

## Rules

- **Only what the Vue page shows.** Same title, text and links, nothing extra for crawlers. Hidden keyword text would be cloaking.
- Admin HTML goes through `App\Services\Seo\SeoPageBody::html()`. It keeps only basic tags (`p, h2–h4, ul/ol/li, strong/em, a, blockquote, table`), drops every attribute except an `http(s)://` or `/` `href`, and skips empty / `.` placeholder texts.
- A new page type: call `$this->page(...)` before `return $this->build(...)` in its meta method. Use `label()` for section headings (en + ka), and `siteSection()` / `shopSection()` for the trail parent.

## Structured data added

- `BreadcrumbList` on every non-home page (from the same trail).
- `WebSite.potentialAction` `SearchAction` (Google's sitelinks search box) on site (`/search_articles`), shop (`/search_products`), blog (`/search`) and films (`/search_films`), all with `?query=`.
- Tours: `TouristTrip.offers` is emitted only when `min_price` holds both a number and a currency sign (`100$`, `200 ₾`).
- Not added: `AggregateRating`. The site's stars are climbers' route ratings, not reviews of the page's subject, and Google's review-snippet rules don't allow that.

## Page locale

`App\Http\Middleware\SetUrlLocale` (in the `web` group) sets `app()->getLocale()` to `ka` when the URL starts with `/ka`. The SPA catch-alls have no route prefix, so the older `setLocale` middleware never ran for them, and `/ka/...` pages output `<html lang="en">`, `og:locale` `en_US` and an English-first `WebSite.inLanguage`.

## Images (audit rules)

- Every `<img>` has `loading` (lazy, except navbars, page loaders and page-top heroes) and `width` / `height`. `services/fun/imageAttrs.js` writes natural sizes after load, neutralised by `:where(img[data-cg-size])` in `_fun.scss`. Details in [FRONTEND.md](../../FRONTEND/FRONTEND.md#images).
- The head slider's first slide is `eager` + `fetchpriority="high"` (the LCP image).

## URLs, status codes and duplicates (audit, November 2026)

A crawl of every sitemap URL plus every `/ka/` version (as Googlebot, local copy) found these problems. All are fixed:

- **Soft 404s → real 404s.** Any URL used to answer `200` with the home page's title and `index, follow`: made-up paths, deleted or unpublished articles. Now, when a detail page's record (article, event, local business, post, product, service, tour, film, summit) is missing, `SeoService::notFound()` returns `status 404`, `noindex, follow`, the title "Page not found | <service>", and no schema. The `*/IndexController`s send that status (`response()->view(..., $seo['status'] ?? 200)`). The SPA still loads and shows its own 404 page. Unknown top-level paths are not 404'd: the SPA has routes the server doesn't list.
- **"Public by URL only" (`published = 2`).** These are real pages (the page APIs accept 1 and 2), so the SEO lookups accept them too, but send `noindex, follow`. They are also left out of the lists and the sitemap.
- **Slugs with `( ) " &` or non-Latin letters got the home page's meta** (36 pages). `$request->path()` is URL-encoded and the slugs are stored decoded, so `segments()` now `rawurldecode`s.
- **One URL form everywhere.** `canonicalUrl()` = scheme + host + each decoded path segment `rawurlencode`d. `GenerateSitemap::slug()`, the hreflang links, `og:url`, schema `url` and the client (`services/seo/pageMeta.js` → `canonical_path()`, checked identical to PHP) all use it. The English home is `https://climbing.ge/` and the Georgian home `/ka`; hreflang for the home used to point at `/ka/`.
- **No canonical / hreflang on `noindex` pages** (404s, `published = 2`, user.climbing.ge): noindex plus canonical is a mixed signal.
- **Blog `/news/<slug>`** was looked up as a blog Post, but it is a guidebook Article, so all of them got the blog home's meta. Now it gets `articleMeta('news')` (NewsArticle schema), with the canonical on `climbing.ge/news/<slug>`. The blog sitemap no longer lists news (a sitemap lists canonical URLs only), and blog posts get hreflang like the other services.
- **Client meta no longer duplicates the server's.** The six `MetaDataComponent`s added a second canonical (`window.location.href`, with `?query` and `#hash`), a second hreflang set and the raw description after JavaScript ran, and Google indexes that rendered DOM. Now the five public ones use `services/seo/pageMeta.js`. On the page the server rendered (which is always the case for Googlebot) the server's tags stay the only ones. After in-app navigation, the server tags (marked `data-seo="server"`) are removed once and the client writes its own, using the same URL and description rules.
- **robots.txt allowed nothing under `/api/`.** Googlebot renders the Vue pages, and their content comes from `/api/get_*`, so the rendered pages had no content. `Allow: /api/get_` (the longest match wins over `Disallow: /api/`). user.climbing.ge isn't disallowed on purpose: its pages say `noindex, nofollow`, and a Disallow would hide that from Google.

## Titles and descriptions

- **Title:** HTML entities decoded. Over 60 characters (what Google shows), the ` | climbing.ge` suffix is dropped first.
- **Description** (`description()` in `build()`), built in this order:
  1. The page's own description, decoded (`&nbsp;` used to show literally) and cut at a word boundary (≤160).
  2. If that is a placeholder (`.`, `-`; fewer than two words, `meaningful()`) or shorter than 70 characters, it is continued with the page's visible text (the crawler body). Nothing that isn't on the page.
  3. If there is still nothing: "<page name> — <service's standard description>" (`serviceDescription()`).
  - Before: 122 pages had `.` as their description and 34 had none.
- **New slugs:** `URLTitleService::get_url_title()` uses `Str::slug($title, '_', 'en')`: lowercase ASCII, digits and `_`, transliterating Georgian and Cyrillic. It used to keep `+ # ? !` and non-Latin letters. Stored slugs are unchanged.

## Renamed slugs → 301

- **Table `url_redirects`** (`segment`, `old_slug`, `new_slug`; binary collation, so the match is exact). Migration `2026_11_06_120000_create_url_redirects_table.php`.
- **Middleware `RedirectRenamedSlugs`** (web group, after `SetUrlLocale`): `/{segment}/{old}` and `/ka/{segment}/{old}` → `301` to the new slug. The language prefix and query string are kept. It runs on every subdomain, so blog and guidebook share `/news/`. The map is cached as `url_redirects:map` and cleared on every change.
- **Automatic:** the trait `App\Models\Concerns\RecordsSlugRedirects` is on Article, Product, Service, Tour, Event, Post, Suport_local_bisnes, Summit and Film. When `url_title` changes through Eloquent (the admin's "change URL title", the summit and event forms), it records old → new for each of the model's `slugSegments()` (articles: their category's path, e.g. `mount_route` → `mountaineering`). Earlier redirects are pointed at the newest slug (no chains). A redirect *from* the new slug is deleted, because that URL is live again.
- **Renames done with SQL bypass Eloquent**, so they must insert their `url_redirects` rows themselves and then run `php artisan cache:forget url_redirects:map`.
- **The 14 legacy slugs** (brackets, quotes, `&`, `+`, a Cyrillic "с") are renamed by `docs/BACKEND/sql-imports/2026_11_06_rename_legacy_slugs.sql`. It inserts the redirects, renames the slugs, and fixes links to the old URLs stored in content (event texts, the header slider, one article). Only full `/{segment}/{old}` paths are replaced, in raw, URL-encoded and HTML-escaped form; the gym is still named "S.K.Lucky". It's safe to run twice. Order: `php artisan migrate` → SQL → `php artisan cache:forget url_redirects:map` → `php artisan app:generate-sitemap`.

## Sitelinks (the extra links under the home result)

Google chooses sitelinks itself, mostly for brand searches ("climbing.ge", "climbing.ge shop") where the site is the top result. No markup turns them on; they come from the site's structure. What feeds them:
- **Sitemaps list every section page** in both languages: guidebook `outdoor`, `mountaineering`, `indoor`, `ice` (priority 0.9), `events`, `other`, `spot_projects`, `partners`, `about_us`; shop `products`, `services`, `tours`, `climbing_wall_colculator`, `sale_products`, `outlet_products`, `about_us`. Every service's home page is listed too (`GenerateSitemap::addHome()` / `addSections()`). Before November 2026 only articles and products were listed, and shop / blog / summit / films had no home entry.
- **Every section has its own title and description.** The shop's named pages come from `resources/lang/seo/{locale}.json → shop_pages`; they used to get the shop home's.
- **The home page links to each section with a short, clear name** (crawler HTML `homeLinks()` + the navbar).
- **`WebSite` structured data:** `name` is the brand ("Climbing.ge", "Climbing.ge Shop"…), with `alternateName`. `url` is exactly the home canonical (`https://climbing.ge/`). Google uses this for the site name shown above the result.
- The `SearchAction` markup stays, but Google retired the sitelinks search box in 2024.

## Structured data, images and the Merchant feed (audit 3, November 2026)

- **Article / NewsArticle** (tech tips, special articles, news) carry `headline` (≤ 110 chars), `author` (the Climbing.ge organization), `datePublished` and `dateModified`. Old rows without `created_at` fall back to the earliest translation's date, then `updated_at`. Google's article results need these; before, there was only `name`, no author, and a `null` date on old rows.
- **SportsActivityLocation** (crags, gyms, ice, mountaineering) is a place: `address` = region + `addressCountry: GE`. Coordinates aren't stored (the maps are "My Maps" embeds). The article-only properties it used to carry (`datePublished`, `publisher`, `inLanguage`) are gone.
- **Every schema `description`** = the cleaned meta description (tours used to send the raw `.`). A description still under 50 characters gets " — <service description>" appended.
- **`truncate()`** turns block-level tags into spaces before `strip_tags()` (paragraph words used to run together). All end-trimming uses `trimEnd()` (Unicode regex). PHP's byte-based `rtrim()` with `… – —` cut Georgian letters in half, and the broken UTF-8 made `json_encode()` fail, so the page's JSON-LD came out empty.
- **Product photos live in `option_images`.** `product_images` is unused, so every product's share image, Product schema and Merchant feed item showed the generic default. `Product::photo_urls()` gives the photos, main first (the cheapest option with photos), with file names `rawurlencode`d (they contain `{…}`).
- **Merchant feed:**
  - `image_link` + up to 5 `additional_image_link`. Products without any photo are left out, because a generic image gets an item disapproved.
  - `description` is the short description, else the product text, else the title (it was `.`).
  - `link` is encoded like the canonical.
- **Local businesses** share their first gallery photo (`suport_local_bisnes_images`). `ContentResolver` uses the real table `suport_local_bisneses`.
- **`/partners`** has its own title and description (`resources/lang/seo/*.json → list_sections.partners`).

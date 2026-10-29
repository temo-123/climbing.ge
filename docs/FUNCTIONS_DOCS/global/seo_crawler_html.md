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

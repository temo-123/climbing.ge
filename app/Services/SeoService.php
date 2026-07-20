<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Guide\Article;
use App\Models\Guide\Event;
use App\Models\Guide\Suport_local_bisnes;
use App\Models\Blog\Post;
use App\Models\Shop\Product;
use App\Models\Shop\Product_option;
use App\Models\Shop\Service;
use App\Models\Shop\Tour;
use App\Models\Summit\Summit;
use App\Services\Seo\KeywordGeneratorService;

class SeoService
{
    /** Loaded once per request from resources/lang/seo/{locale}.json — see keywordTemplate(). */
    private static array $templateCache = [];

    public function forSite(Request $request): array
    {
        $segments = $this->segments($request);
        $locale   = $this->locale($segments);

        if (count($segments) === 0) {
            return $this->siteDefaults($locale);
        }

        $type = $segments[0];
        $slug = $segments[1] ?? null;

        if (! $slug) {
            return $this->siteListMeta($type, $locale);
        }

        $articleCategories = [
            'news'            => 'news',
            'tech_tip'        => 'tech_tip',
            'partner'         => 'partners',
            'special_article' => 'special',
            'spot_project'    => 'spot_projects',
            'mountaineering'  => 'mount_route',
            'outdoor'         => 'outdoor',
            'indoor'          => 'indoor',
            'ice'             => 'ice',
            'other'           => 'other',
        ];

        if (isset($articleCategories[$type])) {
            return $this->articleMeta($slug, $articleCategories[$type], $locale);
        }

        if ($type === 'event') {
            return $this->eventMeta($slug, $locale);
        }

        if ($type === 'local_bisnes') {
            return $this->localBisnesMeta($slug, $locale);
        }

        return $this->siteDefaults($locale);
    }

    public function forBlog(Request $request): array
    {
        $segments = $this->segments($request);
        $locale   = $this->locale($segments);

        if (count($segments) === 0) {
            return $this->blogDefaults($locale);
        }

        $type = $segments[0];
        $slug = $segments[1] ?? null;

        if ($slug && in_array($type, ['post', 'news'])) {
            return $this->blogPostMeta($slug, $locale);
        }

        // Named blog pages
        if ($type === 'about_us') {
            $aboutUs  = $this->keywordTemplate($locale)['about_us'] ?? [];
            $data     = $this->getSiteLocaleData($locale);
            $desc     = $this->truncate(strip_tags($data['blog_short_description'] ?? '')) ?: ($aboutUs['blog_description'] ?? '');
            $title    = $aboutUs['title'] ?? 'About Us';
            $phrases  = array_merge([$title], $this->geoPhrases($locale, 'topics.blog_post'));
            $keywords = app(KeywordGeneratorService::class)->generate($desc, $phrases, $locale);
            return $this->build($title . ' | blog.climbing.ge', $desc, $this->defaultImage(), 'website', null, $keywords);
        }

        return $this->blogDefaults($locale);
    }

    public function forShop(Request $request): array
    {
        $segments = $this->segments($request);
        $locale   = $this->locale($segments);

        if (count($segments) === 0) {
            return $this->shopDefaults($locale);
        }

        $type = $segments[0];
        $slug = $segments[1] ?? null;

        if (! $slug) {
            $data = $this->getSiteLocaleData($locale);
            // Structural lookups only — title/topic come from the keyword template.
            $shopSections = [
                'products' => ['key' => 'products_description', 'img' => 'shop.jpg'],
                'services' => ['key' => 'services_description', 'img' => 'services.jpg'],
                'tours'    => ['key' => 'tour_description',     'img' => null],
            ];
            if (isset($shopSections[$type])) {
                $s           = $shopSections[$type];
                $sectionMeta = $this->keywordTemplate($locale)['shop_sections'][$type] ?? [];
                $sectionTitle = $sectionMeta['title'] ?? ucwords($type);

                $desc     = $this->truncate(strip_tags($data[$s['key']] ?? '')) ?: 'Climbing gear and services.';
                $img      = ($s['img'] && file_exists(public_path('images/meta_img/' . $s['img'])))
                            ? asset('images/meta_img/' . $s['img']) : $this->defaultImage();
                $phrases  = array_merge([$sectionTitle], $this->geoPhrases($locale, "shop_sections.{$type}.topic"));
                $keywords = app(KeywordGeneratorService::class)->generate($desc, $phrases, $locale);
                return $this->build($sectionTitle . ' | shop.climbing.ge', $desc, $img, 'website', null, $keywords);
            }
            return $this->shopDefaults($locale);
        }

        return match ($type) {
            'product' => $this->productMeta($slug, $locale),
            'service' => $this->serviceMeta($slug, $locale),
            'tour'    => $this->tourMeta($slug, $locale),
            default   => $this->shopDefaults($locale),
        };
    }

    public function forSummit(Request $request): array
    {
        $segments = $this->segments($request);
        $locale   = $this->locale($segments);

        if (count($segments) < 2 || $segments[0] !== 'summit') {
            return $this->summitDefaults($locale);
        }

        $slug = $segments[1] ?? null;

        if ($slug) {
            return $this->summitMeta($slug, $locale);
        }

        return $this->summitDefaults($locale);
    }

    public function forFilms(Request $request): array
    {
        $segments = $this->segments($request);
        $locale   = $this->locale($segments);

        if (count($segments) === 0) {
            return $this->filmsDefaults($locale);
        }

        $type = $segments[0];
        $slug = $segments[1] ?? null;

        if ($type === 'film' && $slug) {
            return $this->filmMeta($slug, $locale);
        }

        if ($type === 'about_us') {
            $aboutUs  = $this->keywordTemplate($locale)['about_us'] ?? [];
            $title    = $aboutUs['title'] ?? 'About Us';
            $desc     = $aboutUs['films_description'] ?? '';
            $phrases  = array_merge([$title], $this->geoPhrases($locale, 'topics.film'));
            $keywords = app(KeywordGeneratorService::class)->generate($desc, $phrases, $locale);
            return $this->build($title . ' | films.climbing.ge', $desc, $this->defaultImage(), 'website', null, $keywords);
        }

        return $this->filmsDefaults($locale);
    }

    // ── Internal resolvers ────────────────────────────────────────────────

    private function articleMeta(string $slug, string $category, string $locale): array
    {
        $article = Article::where('url_title', $slug)
            ->where('category', $category)
            ->where('published', true)
            ->with(['global_article_us', 'global_article_ka', 'outdoor_region'])
            ->first();

        if (! $article) {
            return $this->siteDefaults($locale);
        }

        $content  = $locale === 'ka' ? $article->global_article_ka : $article->global_article_us;
        $title    = $content?->title ?? ($article->global_article_us?->title ?? 'climbing.ge');
        $desc     = $this->truncate($content?->short_description ?? $article->global_article_us?->short_description ?? '');
        $imageDir = 'images/' . $category . '_img/';
        $image    = $article->image ? asset($imageDir . $article->image) : $this->defaultImage();

        $regionField = $locale === 'ka' ? 'ka_name' : 'us_name';
        $regionNames = $article->outdoor_region->pluck($regionField)->filter()->take(2)->values()->all();

        $schema = [
            '@context'      => 'https://schema.org',
            '@type'         => $this->articleSchema($category),
            'name'          => $title,
            'description'   => $desc,
            'image'         => $image,
            'url'           => request()->url(),
            'datePublished' => optional($article->created_at)->toIso8601String(),
            'dateModified'  => optional($article->updated_at)->toIso8601String(),
            'inLanguage'    => $locale === 'ka' ? 'ka' : 'en',
            'publisher'     => $this->publisherSchema(),
        ];

        $phrases  = array_merge([$title], $this->geoPhrases($locale, "categories.{$category}", $regionNames));
        $keywords = $content?->meta_keyword ?: $this->keywords(
            "seo_kw:article:{$article->id}:{$locale}:{$article->updated_at?->timestamp}",
            $content?->text ?? $desc,
            $phrases,
            $locale
        );
        $schema['keywords'] = $keywords;

        return $this->build($title . ' | climbing.ge', $desc, $image, 'article', $schema, $keywords);
    }

    private function eventMeta(string $slug, string $locale): array
    {
        $event = Event::where('url_title', $slug)
            ->where('published', true)
            ->with(['us_event', 'ka_event'])
            ->first();

        if (! $event) {
            return $this->siteDefaults($locale);
        }

        $content = $locale === 'ka' ? $event->ka_event : $event->us_event;
        $title   = $content?->title ?? $event->us_event?->title ?? 'Event | climbing.ge';
        $desc    = $this->truncate($content?->short_description ?? '');
        $image   = $event->image ? asset('images/event_img/' . $event->image) : $this->defaultImage();

        $schema = [
            '@context'            => 'https://schema.org',
            '@type'               => 'Event',
            'name'                => $title,
            'description'         => $desc,
            'image'               => $image,
            'url'                 => request()->url(),
            'startDate'           => $event->start_data ?? null,
            'endDate'             => $event->end_data ?? null,
            'inLanguage'          => $locale === 'ka' ? 'ka' : 'en',
            'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
            'eventStatus'         => 'https://schema.org/EventScheduled',
            'organizer'           => $this->publisherSchema(),
            'location'            => [
                '@type'   => 'Place',
                'name'    => 'Georgia',
                'address' => ['@type' => 'PostalAddress', 'addressCountry' => 'GE'],
            ],
        ];

        $phrases  = array_merge([$title], $this->geoPhrases($locale, 'topics.event'));
        $keywords = $this->keywords(
            "seo_kw:event:{$event->id}:{$locale}:{$event->updated_at?->timestamp}",
            $content?->text ?? $desc,
            $phrases,
            $locale
        );
        $schema['keywords'] = $keywords;

        return $this->build($title . ' | climbing.ge', $desc, $image, 'article', $schema, $keywords);
    }

    private function localBisnesMeta(string $slug, string $locale): array
    {
        $bisnes = Suport_local_bisnes::where('url_title', $slug)->first();

        if (! $bisnes) {
            return $this->siteDefaults($locale);
        }

        $bisnes->load(['us_bisnes', 'ka_bisnes']);
        $content = $locale === 'ka' ? $bisnes->ka_bisnes : $bisnes->us_bisnes;
        $title   = $content?->title ?? $bisnes->us_bisnes?->title ?? 'Local Business | climbing.ge';
        $desc    = $this->truncate(strip_tags($content?->short_description ?? $bisnes->us_bisnes?->short_description ?? ''));
        $image   = $this->defaultImage();

        $schema = [
            '@context'    => 'https://schema.org',
            '@type'       => 'LocalBusiness',
            'name'        => $title,
            'description' => $desc,
            'url'         => request()->url(),
            'image'       => $image,
            'address'     => ['@type' => 'PostalAddress', 'addressCountry' => 'GE'],
        ];

        $phrases  = array_merge([$title], $this->geoPhrases($locale, 'topics.local_business'));
        $keywords = $this->keywords(
            "seo_kw:bisnes:{$bisnes->id}:{$locale}:{$bisnes->updated_at?->timestamp}",
            $content?->text ?? $desc,
            $phrases,
            $locale
        );
        $schema['keywords'] = $keywords;

        return $this->build($title . ' | climbing.ge', $desc, $image, 'website', $schema, $keywords);
    }

    private function blogPostMeta(string $slug, string $locale): array
    {
        $post = Post::where('url_title', $slug)
            ->where('published', true)
            ->with(['us_post', 'ka_post'])
            ->first();

        if (! $post) {
            return $this->blogDefaults($locale);
        }

        $content = $locale === 'ka' ? $post->ka_post : $post->us_post;
        $title   = $content?->title ?? $post->us_post?->title ?? 'Post | blog.climbing.ge';
        $desc    = $this->truncate($content?->short_description ?? '');
        $image   = $post->image ? asset('images/blog_img/' . $post->image) : $this->defaultImage();

        $schema = [
            '@context'      => 'https://schema.org',
            '@type'         => 'BlogPosting',
            'headline'      => $title,
            'description'   => $desc,
            'image'         => $image,
            'url'           => request()->url(),
            'datePublished' => optional($post->created_at)->toIso8601String(),
            'dateModified'  => optional($post->updated_at)->toIso8601String(),
            'inLanguage'    => $locale === 'ka' ? 'ka' : 'en',
            'publisher'     => $this->publisherSchema(),
            'author'        => $this->publisherSchema(),
        ];

        $phrases  = array_merge([$title], $this->geoPhrases($locale, 'topics.blog_post'));
        $keywords = $this->keywords(
            "seo_kw:post:{$post->id}:{$locale}:{$post->updated_at?->timestamp}",
            $content?->text ?? $desc,
            $phrases,
            $locale
        );
        $schema['keywords'] = $keywords;

        return $this->build($title . ' | blog.climbing.ge', $desc, $image, 'article', $schema, $keywords);
    }

    private function productMeta(string $slug, string $locale): array
    {
        $product = Product::where('url_title', $slug)
            ->where('published', true)
            ->with(['us_product', 'ka_product'])
            ->first();

        if (! $product) {
            return $this->shopDefaults($locale);
        }

        $content = $locale === 'ka' ? $product->ka_product : $product->us_product;
        $title   = $content?->title ?? $product->us_product?->title ?? 'Product | shop.climbing.ge';
        $desc    = $this->truncate($content?->short_description ?? '');

        $imageModel = \App\Models\Shop\Product_image::where('product_id', $product->id)->first();
        $image = $imageModel?->image ? asset('images/product_img/' . $imageModel->image) : $this->defaultImage();

        $schema = [
            '@context'    => 'https://schema.org',
            '@type'       => 'Product',
            'name'        => $title,
            'description' => $desc,
            'image'       => $image,
            'url'         => request()->url(),
            'brand'       => $this->publisherSchema(),
        ];

        $price = null;
        $option = Product_option::where('product_id', $product->id)->orderBy('price')->first();
        if ($option && $option->price) {
            // `discount` is a percentage (0-100), not an absolute price —
            // see ProductPage.vue's colculate_discount_actyve_price().
            $finalPrice = $option->discount > 0
                ? round($option->price - ($option->discount * $option->price / 100), 2)
                : (float) $option->price;

            $inStock = ProductService::get_option_stock_quantity($option) > 0;

            $price = [
                'amount'   => $finalPrice,
                'currency' => $this->isoCurrency($option->currency),
                'inStock'  => $inStock,
            ];

            $schema['offers'] = [
                '@type'         => 'Offer',
                'price'         => $finalPrice,
                'priceCurrency' => $price['currency'],
                'availability'  => $inStock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url'           => request()->url(),
            ];
        }

        $phrases  = array_merge([$title], $this->geoPhrases($locale, 'topics.product'));
        $keywords = $this->keywords(
            "seo_kw:product:{$product->id}:{$locale}:{$product->updated_at?->timestamp}",
            $content?->text ?? $desc,
            $phrases,
            $locale
        );
        $schema['keywords'] = $keywords;

        return $this->build($title . ' | shop.climbing.ge', $desc, $image, 'product', $schema, $keywords, $price);
    }

    private function serviceMeta(string $slug, string $locale): array
    {
        $service = Service::where('url_title', $slug)
            ->where('published', true)
            ->with(['us_service', 'ka_service', 'service_images'])
            ->first();

        if (! $service) {
            return $this->shopDefaults($locale);
        }

        $content = $locale === 'ka' ? $service->ka_service : $service->us_service;
        $title   = $content?->title ?? $service->us_service?->title ?? 'Service | shop.climbing.ge';
        $desc    = $this->truncate($content?->short_description ?? '');

        $firstImage = $service->service_images?->first();
        $image = $firstImage?->image ? asset('images/service_img/' . $firstImage->image) : $this->defaultImage();

        $schema = [
            '@context'    => 'https://schema.org',
            '@type'       => 'Service',
            'name'        => $title,
            'description' => $desc,
            'image'       => $image,
            'url'         => request()->url(),
            'provider'    => $this->publisherSchema(),
            'areaServed'  => ['@type' => 'Country', 'name' => 'Georgia'],
        ];

        $phrases  = array_merge([$title], $this->geoPhrases($locale, 'topics.service'));
        $keywords = $content?->meta_keyword ?: $this->keywords(
            "seo_kw:service:{$service->id}:{$locale}:{$service->updated_at?->timestamp}",
            $content?->text ?? $desc,
            $phrases,
            $locale
        );
        $schema['keywords'] = $keywords;

        return $this->build($title . ' | shop.climbing.ge', $desc, $image, 'website', $schema, $keywords);
    }

    private function tourMeta(string $slug, string $locale): array
    {
        $tour = Tour::where('url_title', $slug)
            ->where('published', true)
            ->with(['us_tour', 'ka_tour', 'tour_images'])
            ->first();

        if (! $tour) {
            return $this->shopDefaults($locale);
        }

        $content = $locale === 'ka' ? $tour->ka_tour : $tour->us_tour;
        $title   = $content?->title ?? $tour->us_tour?->title ?? 'Tour | shop.climbing.ge';
        $desc    = $this->truncate($content?->short_description ?? '');

        $firstImage = $tour->tour_images?->first();
        $image = $firstImage?->image ? asset('images/tour_img/' . $firstImage->image) : $this->defaultImage();

        $schema = [
            '@context'    => 'https://schema.org',
            '@type'       => 'TouristTrip',
            'name'        => $title,
            'description' => $desc,
            'image'       => $image,
            'url'         => request()->url(),
        ];

        if ($content?->duration) {
            $schema['itinerary'] = ['@type' => 'ItemList', 'name' => $content->duration];
        }

        $phrases  = array_merge([$title], $this->geoPhrases($locale, 'topics.tour'));
        $keywords = $this->keywords(
            "seo_kw:tour:{$tour->id}:{$locale}:{$tour->updated_at?->timestamp}",
            $content?->text ?? $desc,
            $phrases,
            $locale
        );
        $schema['keywords'] = $keywords;

        return $this->build($title . ' | shop.climbing.ge', $desc, $image, 'article', $schema, $keywords);
    }

    private function filmMeta(string $slug, string $locale): array
    {
        $film = \App\Models\Films\Film::where('url_title', $slug)
            ->where('published', true)
            ->with(['us_film', 'ka_film'])
            ->first();

        if (! $film) {
            return $this->filmsDefaults($locale);
        }

        $content = $locale === 'ka' ? $film->ka_film : $film->us_film;
        $title   = $content?->name ?? $film->us_film?->name ?? 'Film | films.climbing.ge';
        $desc    = $this->truncate($content?->short_description ?? $film->us_film?->short_description ?? '');
        $image   = $film->image ? asset('images/film_img/' . $film->image) : $this->defaultImage();

        $schema = [
            '@context'    => 'https://schema.org',
            '@type'       => 'Movie',
            'name'        => $title,
            'description' => $desc,
            'image'       => $image,
            'url'         => request()->url(),
        ];
        if ($content?->coutry) {
            $schema['countryOfOrigin'] = ['@type' => 'Country', 'name' => $content->coutry];
        }

        $extraLocations = $content?->coutry ? [$content->coutry] : [];
        $phrases  = array_merge([$title], $this->geoPhrases($locale, 'topics.film', $extraLocations));
        $keywords = $this->keywords(
            "seo_kw:film:{$film->id}:{$locale}:{$film->updated_at?->timestamp}",
            $content?->text ?? $desc,
            $phrases,
            $locale
        );
        $schema['keywords'] = $keywords;

        return $this->build($title . ' | films.climbing.ge', $desc, $image, 'video.movie', $schema, $keywords);
    }

    private function summitMeta(string $slug, string $locale): array
    {
        $summit = Summit::where('url_title', $slug)
            ->where('published', true)
            ->first();

        if (! $summit) {
            return $this->summitDefaults($locale);
        }

        $title = ($locale === 'ka' ? $summit->ka_title : $summit->title) ?? $summit->title ?? 'Summit | summit.climbing.ge';
        $desc  = $this->truncate(($locale === 'ka' ? $summit->ka_description : $summit->description) ?? '');
        $image = $summit->image ? asset('images/summit_img/' . $summit->image) : $this->defaultImage();

        $geo = ['@type' => 'GeoCoordinates'];
        if ($summit->height)    { $geo['elevation']  = $summit->height; }
        if ($summit->latitude)  { $geo['latitude']   = $summit->latitude; }
        if ($summit->longitude) { $geo['longitude']  = $summit->longitude; }

        $schema = [
            '@context'             => 'https://schema.org',
            '@type'                => 'Mountain',
            'name'                 => $title,
            'description'          => $desc,
            'image'                => $image,
            'url'                  => request()->url(),
            'containedInPlace'     => ['@type' => 'Country', 'name' => 'Georgia', 'identifier' => 'GE'],
        ];

        if (count($geo) > 1) {
            $schema['geo'] = $geo;
        }

        if ($summit->height) {
            $schema['additionalProperty'] = [
                '@type'     => 'PropertyValue',
                'name'      => 'elevation',
                'value'     => $summit->height,
                'unitCode'  => 'MTR',
            ];
        }

        $phrases  = array_merge([$title], $this->geoPhrases($locale, 'topics.summit'));
        $keywords = $this->keywords(
            "seo_kw:summit:{$summit->id}:{$locale}:{$summit->updated_at?->timestamp}",
            $desc,
            $phrases,
            $locale
        );
        $schema['keywords'] = $keywords;

        return $this->build($title . ' | summit.climbing.ge', $desc, $image, 'article', $schema, $keywords);
    }

    private function siteListMeta(string $type, string $locale): array
    {
        $data = $this->getSiteLocaleData($locale);

        // Structural lookups only (DB field name + asset filename) — display
        // title and keyword topic come from the keyword template (locale-aware).
        $sections = [
            'outdoor'         => ['key' => 'outdoor_description',        'img' => 'outdoor.jpg'],
            'mountaineering'  => ['key' => 'mount_description',          'img' => 'mount.jpg'],
            'indoor'          => ['key' => 'indoor_description',         'img' => 'indoor.jpg'],
            'ice'             => ['key' => 'ice_description',            'img' => 'ice.jpg'],
            'events'          => ['key' => 'event_description',          'img' => null],
            'other'           => ['key' => 'other_activity_description', 'img' => 'other.jpg'],
            'news'            => ['key' => 'news_description',           'img' => null],
            'tech_tip'        => ['key' => 'tech_tips_description',      'img' => null],
            'spot_projects'   => ['key' => 'guid_short_description',     'img' => null],
            'about_us'        => ['key' => 'guid_short_description',     'img' => null],
            'search_articles' => ['key' => 'guide_search_description',   'img' => null],
        ];

        if (! isset($sections[$type])) {
            return $this->siteDefaults($locale);
        }

        $section     = $sections[$type];
        $sectionMeta = $this->keywordTemplate($locale)['list_sections'][$type] ?? [];
        $sectionTitle = $sectionMeta['title'] ?? ucwords(str_replace('_', ' ', $type));

        $desc    = $this->truncate(strip_tags($data[$section['key']] ?? '')) ?: 'Georgian rock climbing guidebook.';
        $title   = $sectionTitle . ' | climbing.ge';
        $imgFile = $section['img'];
        $image   = ($imgFile && file_exists(public_path('images/meta_img/' . $imgFile)))
                    ? asset('images/meta_img/' . $imgFile)
                    : $this->defaultImage();

        $phrases  = array_merge([$sectionTitle], $this->geoPhrases($locale, "list_sections.{$type}.topic"));
        $keywords = app(KeywordGeneratorService::class)->generate($desc, $phrases, $locale);

        return $this->build($title, $desc, $image, 'website', null, $keywords);
    }

    private function getSiteLocaleData(string $locale): array
    {
        $key  = $locale === 'ka' ? 'ka' : 'us';
        $all  = \App\Services\Abstract\LocaleSiteService::getGroupedByLocale();
        $data = $all[$key] ?? ($all['us'] ?? null);

        return $data ? (array) $data : [];
    }

    // ── Defaults ──────────────────────────────────────────────────────────

    /**
     * Title, description and keyword topic for a subdomain's fallback page
     * all come from resources/lang/seo/{locale}.json#defaults.{key}.
     */
    private function defaultsFor(string $locale, string $key): array
    {
        $tpl   = $this->keywordTemplate($locale)['defaults'][$key] ?? [];
        $title = $tpl['title'] ?? 'climbing.ge';
        $desc  = $tpl['description'] ?? '';

        $phrases = $this->geoPhrases($locale, "defaults.{$key}.topic");

        return $this->build(
            $title,
            $desc,
            $this->defaultImage(),
            'website',
            null,
            app(KeywordGeneratorService::class)->generate($desc, $phrases, $locale)
        );
    }

    private function siteDefaults(string $locale = 'us'): array
    {
        return $this->defaultsFor($locale, 'site');
    }

    private function blogDefaults(string $locale = 'us'): array
    {
        return $this->defaultsFor($locale, 'blog');
    }

    private function filmsDefaults(string $locale = 'us'): array
    {
        return $this->defaultsFor($locale, 'films');
    }

    private function shopDefaults(string $locale = 'us'): array
    {
        return $this->defaultsFor($locale, 'shop');
    }

    private function summitDefaults(string $locale = 'us'): array
    {
        return $this->defaultsFor($locale, 'summit');
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    private function build(string $title, string $description, string $image, string $type, ?array $schema = null, string $keywords = '', ?array $price = null): array
    {
        return [
            'title'       => $title,
            'description' => $description,
            'image'       => $image,
            'type'        => $type,
            'schema'      => $schema,
            'url'         => request()->url(),
            'keywords'    => $keywords,
            'price'       => $price,
        ];
    }

    /**
     * Cache key already embeds the record's updated_at timestamp, so a
     * content edit naturally invalidates it — no manual cache clearing needed.
     */
    private function keywords(string $cacheKey, string $text, array $phrases, string $locale): string
    {
        return Cache::rememberForever($cacheKey, fn () => app(KeywordGeneratorService::class)->generate($text, $phrases, $locale));
    }

    /**
     * Loads resources/lang/seo/{en|ka}.json — the editable keyword/title/
     * description templates — once per request and caches it statically.
     */
    private function keywordTemplate(string $locale): array
    {
        $file = $locale === 'ka' ? 'ka' : 'en';

        if (! isset(self::$templateCache[$file])) {
            $path = resource_path("lang/seo/{$file}.json");
            self::$templateCache[$file] = file_exists($path)
                ? (json_decode(file_get_contents($path), true) ?? [])
                : [];
        }

        return self::$templateCache[$file];
    }

    /**
     * Look up a dot-notation path in the locale's keyword template and
     * always return an array of phrases (templates may store either a
     * single string or a list).
     */
    private function template(string $locale, string $path): array
    {
        $value = data_get($this->keywordTemplate($locale), $path);

        if ($value === null) {
            return [];
        }

        return is_array($value) ? $value : [$value];
    }

    /**
     * Locale-aware "topic + country/region" combos, e.g. "rock climbing Georgia",
     * "rock climbing in Georgia" / "კლდეზე ცოცვა საქართველო", "კლდეზე ცოცვა საქართველოში".
     * $topicPaths are dot-notation lookups into the keyword template (e.g.
     * "topics.product" or "categories.outdoor"). $extraLocations are
     * additional place names already in the right locale (e.g. a Region's
     * us_name/ka_name) — no geocoding, just data already on hand.
     */
    private function geoPhrases(string $locale, string|array $topicPaths, array $extraLocations = []): array
    {
        $kw = app(KeywordGeneratorService::class);

        $topics = [];
        foreach ((array) $topicPaths as $path) {
            $topics = array_merge($topics, $this->template($locale, $path));
        }

        $country   = $this->keywordTemplate($locale)['country'] ?? ($locale === 'ka' ? 'საქართველო' : 'Georgia');
        $locations = $kw->locationVariants($country, $locale);
        foreach ($extraLocations as $place) {
            $locations = array_merge($locations, $kw->locationVariants($place, $locale));
        }

        return $kw->withLocations($topics, $locations);
    }

    private function segments(Request $request): array
    {
        $path = trim($request->path(), '/');

        if ($path === '') {
            return [];
        }

        $parts = explode('/', $path);

        // Strip leading locale segment (ka, us, en)
        if (in_array($parts[0] ?? '', ['ka', 'us', 'en'])) {
            array_shift($parts);
        }

        return $parts;
    }

    private function locale(array $segments): string
    {
        // Re-check original path for locale prefix
        $path = trim(request()->path(), '/');
        $parts = explode('/', $path);

        return ($parts[0] ?? '') === 'ka' ? 'ka' : 'us';
    }

    private function truncate(string $text, int $length = 160): string
    {
        $text = strip_tags($text);
        $text = preg_replace('/\s+/', ' ', trim($text));

        if (mb_strlen($text) <= $length) {
            return $text;
        }

        return rtrim(mb_substr($text, 0, $length)) . '…';
    }

    private function articleSchema(string $category): string
    {
        return match ($category) {
            'news'             => 'NewsArticle',
            'tech_tip'         => 'Article',
            'special'          => 'Article',
            'partners'         => 'Organization',
            'spot_projects'    => 'SportsActivityLocation',
            'mount_route'      => 'SportsActivityLocation',
            'outdoor', 'ice'   => 'SportsActivityLocation',
            'indoor'           => 'SportsActivityLocation',
            default            => 'Article',
        };
    }

    private function publisherSchema(): array
    {
        return [
            '@type' => 'Organization',
            'name'  => 'climbing.ge',
            'url'   => 'https://climbing.ge',
            'logo'  => [
                '@type' => 'ImageObject',
                'url'   => asset('images/site_img/x.png'),
            ],
        ];
    }

    private function defaultImage(): string
    {
        return asset('images/meta_img/default.jpg');
    }

    /**
     * Product_option::currency stores a display symbol (e.g. "₾"), but Google
     * and Facebook's product structured data both require an ISO 4217 code.
     */
    private function isoCurrency(?string $currency): string
    {
        return match ($currency) {
            '₾', 'GEL' => 'GEL',
            '$', 'USD' => 'USD',
            '€', 'EUR' => 'EUR',
            default    => 'GEL',
        };
    }
}

<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Guide\Article;
use App\Models\Guide\Event;
use App\Models\Guide\Suport_local_bisnes;
use App\Models\Blog\Post;
use App\Models\Shop\Product;
use App\Models\Shop\Service;
use App\Models\Shop\Tour;
use App\Models\Summit\Summit;

class SeoService
{
    public function forSite(Request $request): array
    {
        $segments = $this->segments($request);
        $locale   = $this->locale($segments);

        if (count($segments) === 0) {
            return $this->siteDefaults();
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

        return $this->siteDefaults();
    }

    public function forBlog(Request $request): array
    {
        $segments = $this->segments($request);
        $locale   = $this->locale($segments);

        if (count($segments) === 0) {
            return $this->blogDefaults();
        }

        $type = $segments[0];
        $slug = $segments[1] ?? null;

        if ($slug && in_array($type, ['post', 'news'])) {
            return $this->blogPostMeta($slug, $locale);
        }

        // Named blog pages
        if ($type === 'about_us') {
            $data  = $this->getSiteLocaleData($locale);
            $desc  = $this->truncate(strip_tags($data['blog_short_description'] ?? ''));
            return $this->build('About Us | blog.climbing.ge', $desc ?: 'About the climbing.ge blog.', $this->defaultImage(), 'website');
        }

        return $this->blogDefaults();
    }

    public function forShop(Request $request): array
    {
        $segments = $this->segments($request);
        $locale   = $this->locale($segments);

        if (count($segments) === 0) {
            return $this->shopDefaults();
        }

        $type = $segments[0];
        $slug = $segments[1] ?? null;

        if (! $slug) {
            $data = $this->getSiteLocaleData($locale);
            $shopSections = [
                'products' => ['key' => 'products_description', 'title' => 'Climbing Products', 'img' => 'shop.jpg'],
                'services' => ['key' => 'services_description', 'title' => 'Climbing Services',  'img' => 'services.jpg'],
                'tours'    => ['key' => 'tour_description',     'title' => 'Climbing Tours',      'img' => null],
            ];
            if (isset($shopSections[$type])) {
                $s     = $shopSections[$type];
                $desc  = $this->truncate(strip_tags($data[$s['key']] ?? ''));
                $img   = ($s['img'] && file_exists(public_path('images/meta_img/' . $s['img'])))
                            ? asset('images/meta_img/' . $s['img']) : $this->defaultImage();
                return $this->build($s['title'] . ' | shop.climbing.ge', $desc ?: 'Climbing gear and services.', $img, 'website');
            }
            return $this->shopDefaults();
        }

        return match ($type) {
            'product' => $this->productMeta($slug, $locale),
            'service' => $this->serviceMeta($slug, $locale),
            'tour'    => $this->tourMeta($slug, $locale),
            default   => $this->shopDefaults(),
        };
    }

    public function forSummit(Request $request): array
    {
        $segments = $this->segments($request);
        $locale   = $this->locale($segments);

        if (count($segments) < 2 || $segments[0] !== 'summit') {
            return $this->summitDefaults();
        }

        $slug = $segments[1] ?? null;

        if ($slug) {
            return $this->summitMeta($slug, $locale);
        }

        return $this->summitDefaults();
    }

    // ── Internal resolvers ────────────────────────────────────────────────

    private function articleMeta(string $slug, string $category, string $locale): array
    {
        $article = Article::where('url_title', $slug)
            ->where('category', $category)
            ->where('published', true)
            ->with(['global_article_us', 'global_article_ka'])
            ->first();

        if (! $article) {
            return $this->siteDefaults();
        }

        $content  = $locale === 'ka' ? $article->global_article_ka : $article->global_article_us;
        $title    = $content?->title ?? ($article->global_article_us?->title ?? 'climbing.ge');
        $desc     = $this->truncate($content?->short_description ?? $article->global_article_us?->short_description ?? '');
        $imageDir = 'images/' . $category . '_img/';
        $image    = $article->image ? asset($imageDir . $article->image) : $this->defaultImage();

        $schema = [
            '@context' => 'https://schema.org',
            '@type'    => $this->articleSchema($category),
            'name'     => $title,
            'description' => $desc,
            'image'    => $image,
            'url'      => request()->url(),
        ];

        if ($content?->meta_keyword) {
            $schema['keywords'] = $content->meta_keyword;
        }

        return $this->build($title . ' | climbing.ge', $desc, $image, 'article', $schema);
    }

    private function eventMeta(string $slug, string $locale): array
    {
        $event = Event::where('url_title', $slug)
            ->where('published', true)
            ->with(['us_event', 'ka_event'])
            ->first();

        if (! $event) {
            return $this->siteDefaults();
        }

        $content = $locale === 'ka' ? $event->ka_event : $event->us_event;
        $title   = $content?->title ?? $event->us_event?->title ?? 'Event | climbing.ge';
        $desc    = $this->truncate($content?->short_description ?? '');
        $image   = $event->image ? asset('images/event_img/' . $event->image) : $this->defaultImage();

        $schema = [
            '@context'  => 'https://schema.org',
            '@type'     => 'Event',
            'name'      => $title,
            'description' => $desc,
            'image'     => $image,
            'url'       => request()->url(),
            'startDate' => $event->start_data ?? null,
            'endDate'   => $event->end_data ?? null,
        ];

        return $this->build($title . ' | climbing.ge', $desc, $image, 'article', $schema);
    }

    private function localBisnesMeta(string $slug, string $locale): array
    {
        $bisnes = Suport_local_bisnes::where('url_title', $slug)->first();

        if (! $bisnes) {
            return $this->siteDefaults();
        }

        $title = $bisnes->name ?? 'Local Business | climbing.ge';
        $desc  = $this->truncate($bisnes->short_description ?? '');
        $image = $this->defaultImage();

        return $this->build($title . ' | climbing.ge', $desc, $image, 'article');
    }

    private function blogPostMeta(string $slug, string $locale): array
    {
        $post = Post::where('url_title', $slug)
            ->where('published', true)
            ->with(['us_post', 'ka_post'])
            ->first();

        if (! $post) {
            return $this->blogDefaults();
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
        ];

        return $this->build($title . ' | blog.climbing.ge', $desc, $image, 'article', $schema);
    }

    private function productMeta(string $slug, string $locale): array
    {
        $product = Product::where('url_title', $slug)
            ->where('published', true)
            ->with(['us_product', 'ka_product'])
            ->first();

        if (! $product) {
            return $this->shopDefaults();
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
        ];

        return $this->build($title . ' | shop.climbing.ge', $desc, $image, 'product', $schema);
    }

    private function serviceMeta(string $slug, string $locale): array
    {
        $service = Service::where('url_title', $slug)
            ->where('published', true)
            ->with(['us_service', 'ka_service', 'service_images'])
            ->first();

        if (! $service) {
            return $this->shopDefaults();
        }

        $content = $locale === 'ka' ? $service->ka_service : $service->us_service;
        $title   = $content?->title ?? $service->us_service?->title ?? 'Service | shop.climbing.ge';
        $desc    = $this->truncate($content?->short_description ?? '');

        $firstImage = $service->service_images?->first();
        $image = $firstImage?->image ? asset('images/service_img/' . $firstImage->image) : $this->defaultImage();

        return $this->build($title . ' | shop.climbing.ge', $desc, $image, 'website');
    }

    private function tourMeta(string $slug, string $locale): array
    {
        $tour = Tour::where('url_title', $slug)
            ->where('published', true)
            ->with(['us_tour', 'ka_tour', 'tour_images'])
            ->first();

        if (! $tour) {
            return $this->shopDefaults();
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

        return $this->build($title . ' | shop.climbing.ge', $desc, $image, 'article', $schema);
    }

    private function summitMeta(string $slug, string $locale): array
    {
        $summit = Summit::where('url_title', $slug)
            ->where('published', true)
            ->first();

        if (! $summit) {
            return $this->summitDefaults();
        }

        $title = ($locale === 'ka' ? $summit->ka_title : $summit->title) ?? $summit->title ?? 'Summit | summit.climbing.ge';
        $desc  = $this->truncate(($locale === 'ka' ? $summit->ka_description : $summit->description) ?? '');
        $image = $summit->image ? asset('images/summit_img/' . $summit->image) : $this->defaultImage();

        $schema = [
            '@context'    => 'https://schema.org',
            '@type'       => 'Place',
            'name'        => $title,
            'description' => $desc,
            'image'       => $image,
            'url'         => request()->url(),
        ];

        if ($summit->height) {
            $schema['geo'] = ['@type' => 'GeoCoordinates', 'elevation' => $summit->height];
        }

        if ($summit->latitude && $summit->longitude) {
            $schema['geo'] = array_merge($schema['geo'] ?? ['@type' => 'GeoCoordinates'], [
                'latitude'  => $summit->latitude,
                'longitude' => $summit->longitude,
            ]);
        }

        return $this->build($title . ' | summit.climbing.ge', $desc, $image, 'article', $schema);
    }

    private function siteListMeta(string $type, string $locale): array
    {
        $data = $this->getSiteLocaleData($locale);

        $sections = [
            'outdoor'         => ['key' => 'outdoor_description',        'title' => 'Outdoor Climbing Georgia',       'img' => 'outdoor.jpg'],
            'mountaineering'  => ['key' => 'mount_description',          'title' => 'Mountaineering Routes Georgia',  'img' => 'mount.jpg'],
            'indoor'          => ['key' => 'indoor_description',         'title' => 'Indoor Climbing Georgia',        'img' => 'indoor.jpg'],
            'ice'             => ['key' => 'ice_description',            'title' => 'Ice Climbing Georgia',           'img' => 'ice.jpg'],
            'events'          => ['key' => 'event_description',          'title' => 'Climbing Events Georgia',        'img' => null],
            'other'           => ['key' => 'other_activity_description', 'title' => 'Other Activities Georgia',       'img' => 'other.jpg'],
            'news'            => ['key' => 'news_description',           'title' => 'Climbing News Georgia',          'img' => null],
            'tech_tip'        => ['key' => 'tech_tips_description',      'title' => 'Climbing Tech Tips',            'img' => null],
            'spot_projects'   => ['key' => 'guid_short_description',     'title' => 'Spot Projects Georgia',         'img' => null],
            'about_us'        => ['key' => 'guid_short_description',     'title' => 'About Us | climbing.ge',        'img' => null],
            'search_articles' => ['key' => 'guide_search_description',   'title' => 'Search',                       'img' => null],
        ];

        if (! isset($sections[$type])) {
            return $this->siteDefaults();
        }

        $section = $sections[$type];
        $desc    = $this->truncate(strip_tags($data[$section['key']] ?? ''));
        $title   = $section['title'] . ' | climbing.ge';
        $imgFile = $section['img'];
        $image   = ($imgFile && file_exists(public_path('images/meta_img/' . $imgFile)))
                    ? asset('images/meta_img/' . $imgFile)
                    : $this->defaultImage();

        return $this->build($title, $desc ?: 'Georgian rock climbing guidebook.', $image, 'website');
    }

    private function getSiteLocaleData(string $locale): array
    {
        $key  = $locale === 'ka' ? 'ka' : 'us';
        $all  = \App\Services\Abstract\LocaleSiteService::getGroupedByLocale();
        $data = $all[$key] ?? ($all['us'] ?? null);

        return $data ? (array) $data : [];
    }

    // ── Defaults ──────────────────────────────────────────────────────────

    private function siteDefaults(): array
    {
        return $this->build(
            'climbing.ge — Georgian Climbing Guidebook',
            'Georgian rock climbing guidebook, mountaineering routes, outdoor destinations, events and community.',
            $this->defaultImage(),
            'website'
        );
    }

    private function blogDefaults(): array
    {
        return $this->build(
            'blog.climbing.ge — Climbing Blog',
            'News, articles and stories from the Georgian climbing community.',
            $this->defaultImage(),
            'website'
        );
    }

    private function shopDefaults(): array
    {
        return $this->build(
            'shop.climbing.ge — Climbing Gear & Tours',
            'Climbing gear, equipment, guided tours and services in Georgia.',
            $this->defaultImage(),
            'website'
        );
    }

    private function summitDefaults(): array
    {
        return $this->build(
            'summit.climbing.ge — Georgian Summits',
            'Summit log, ascent records and mountain routes in Georgia.',
            $this->defaultImage(),
            'website'
        );
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    private function build(string $title, string $description, string $image, string $type, ?array $schema = null): array
    {
        return [
            'title'       => $title,
            'description' => $description,
            'image'       => $image,
            'type'        => $type,
            'schema'      => $schema,
            'url'         => request()->url(),
        ];
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

    private function defaultImage(): string
    {
        return asset('images/meta_img/default.jpg');
    }
}

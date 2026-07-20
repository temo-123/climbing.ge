<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;
use Carbon\Carbon;

use App\Models\Guide\Article;
use App\Models\Guide\Event;
use App\Models\Guide\Suport_local_bisnes;

use App\Models\Blog\Post;

use App\Models\Shop\Product;
use App\Models\Shop\Service;
use App\Models\Shop\Tour;

use App\Models\Summit\Summit;

class GenerateSitemap extends Command
{
    protected $signature   = 'app:generate-sitemap';
    protected $description = 'Generate XML sitemaps for all subdomains';

    private string $ssh;
    private string $siteUrl;
    private string $shopUrl;
    private string $blogUrl;
    private string $summitUrl;

    public function handle(): void
    {
        $this->ssh       = env('APP_SSH', 'https://');
        $this->siteUrl   = env('SITE_URL', 'climbing.ge');
        $this->shopUrl   = env('SHOP_URL',   'shop.climbing.ge');
        $this->blogUrl   = env('BLOG_URL',   'blog.climbing.ge');
        $this->summitUrl = env('SUMMIT_URL', 'summit.climbing.ge');

        $this->generateGuideSitemap();
        $this->generateShopSitemap();
        $this->generateBlogSitemap();
        $this->generateSummitSitemap();
        $this->generateSitemapIndex();

        $this->info('All sitemaps generated successfully.');
    }

    // ── Guide (climbing.ge) ───────────────────────────────────────────────

    private function generateGuideSitemap(): void
    {
        $sitemap = Sitemap::create();
        $base    = $this->ssh . $this->siteUrl;

        foreach (['', 'ka/'] as $lang) {
            $isKa   = $lang !== '';
            $prefix = $base . '/' . $lang;

            // Static pages
            $sitemap->add(Url::create($base . ($isKa ? '/ka' : '/'))->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));

            $categoryMap = [
                'outdoor'        => 'outdoor',
                'indoor'         => 'indoor',
                'ice'            => 'ice',
                'mount_route'    => 'mountaineering',
                'other'          => 'other',
                'news'           => 'news',
                'special'        => 'special_article',
                'tech_tip'       => 'tech_tip',
                'spot_projects'  => 'spot_projects',
                'partners'       => 'partner',
            ];

            foreach ($categoryMap as $category => $segment) {
                Article::where('category', $category)
                    ->where('published', true)
                    ->get(['url_title', 'updated_at'])
                    ->each(function (Article $a) use ($sitemap, $prefix, $segment) {
                        $sitemap->add(
                            Url::create($prefix . $segment . '/' . $a->url_title)
                                ->setPriority($this->articlePriority($segment))
                                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                                ->setLastModificationDate($a->updated_at ?? Carbon::now())
                        );
                    });
            }

            // Events
            Event::where('published', true)
                ->get(['url_title', 'updated_at'])
                ->each(function (Event $e) use ($sitemap, $prefix) {
                    $sitemap->add(
                        Url::create($prefix . 'event/' . $e->url_title)
                            ->setPriority(0.8)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setLastModificationDate($e->updated_at ?? Carbon::now())
                    );
                });

            // Local businesses
            Suport_local_bisnes::where('published', true)
                ->get(['url_title', 'updated_at'])
                ->each(function (Suport_local_bisnes $b) use ($sitemap, $prefix) {
                    $sitemap->add(
                        Url::create($prefix . 'local_bisnes/' . $b->url_title)
                            ->setPriority(0.6)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                            ->setLastModificationDate($b->updated_at ?? Carbon::now())
                    );
                });
        }

        $sitemap->writeToFile(public_path('guide-sitemap.xml'));
    }

    // ── Shop (shop.climbing.ge) ───────────────────────────────────────────

    private function generateShopSitemap(): void
    {
        $sitemap = Sitemap::create();
        $base    = $this->ssh . $this->shopUrl;

        foreach (['', 'ka/'] as $lang) {
            $prefix = $base . '/' . $lang;

            Product::where('published', true)->get(['url_title', 'updated_at'])->each(function (Product $p) use ($sitemap, $prefix) {
                $sitemap->add(
                    Url::create($prefix . 'product/' . $p->url_title)
                        ->setPriority(0.9)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setLastModificationDate($p->updated_at ?? Carbon::now())
                );
            });

            Service::where('published', true)->get(['url_title', 'updated_at'])->each(function (Service $s) use ($sitemap, $prefix) {
                $sitemap->add(
                    Url::create($prefix . 'service/' . $s->url_title)
                        ->setPriority(0.7)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setLastModificationDate($s->updated_at ?? Carbon::now())
                );
            });

            Tour::where('published', true)->get(['url_title', 'updated_at'])->each(function (Tour $t) use ($sitemap, $prefix) {
                $sitemap->add(
                    Url::create($prefix . 'tour/' . $t->url_title)
                        ->setPriority(0.8)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setLastModificationDate($t->updated_at ?? Carbon::now())
                );
            });
        }

        $sitemap->writeToFile(public_path('shop-sitemap.xml'));
    }

    // ── Blog (blog.climbing.ge) ───────────────────────────────────────────

    private function generateBlogSitemap(): void
    {
        $sitemap = Sitemap::create();
        $base    = $this->ssh . $this->blogUrl;

        foreach (['', 'ka/'] as $lang) {
            $prefix = $base . '/' . $lang;

            Post::where('published', true)->get(['url_title', 'updated_at'])->each(function (Post $p) use ($sitemap, $prefix) {
                $sitemap->add(
                    Url::create($prefix . 'post/' . $p->url_title)
                        ->setPriority(0.7)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setLastModificationDate($p->updated_at ?? Carbon::now())
                );
            });

            // The blog subdomain also serves guide "news" articles at /news/{url_title}
            // (see PostController::get_news / BlogRoutes.js), so they belong in this sitemap too.
            Article::where('category', 'news')->where('published', true)->get(['url_title', 'updated_at'])->each(function (Article $n) use ($sitemap, $prefix) {
                $sitemap->add(
                    Url::create($prefix . 'news/' . $n->url_title)
                        ->setPriority(0.7)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setLastModificationDate($n->updated_at ?? Carbon::now())
                );
            });
        }

        $sitemap->writeToFile(public_path('blog-sitemap.xml'));
    }

    // ── Summit (summit.climbing.ge) ───────────────────────────────────────

    private function generateSummitSitemap(): void
    {
        $sitemap = Sitemap::create();
        $base    = $this->ssh . $this->summitUrl;

        foreach (['', 'ka/'] as $lang) {
            $prefix = $base . '/' . $lang;

            Summit::where('published', true)->get(['url_title', 'updated_at'])->each(function (Summit $s) use ($sitemap, $prefix) {
                $sitemap->add(
                    Url::create($prefix . 'summit/' . $s->url_title)
                        ->setPriority(0.9)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setLastModificationDate($s->updated_at ?? Carbon::now())
                );
            });
        }

        $sitemap->writeToFile(public_path('summit-sitemap.xml'));
    }

    // ── Sitemap index (sitemap.xml) ───────────────────────────────────────

    private function generateSitemapIndex(): void
    {
        $base = $this->ssh . $this->siteUrl;

        SitemapIndex::create()
            ->add($base . '/guide-sitemap.xml')
            ->add($this->ssh . $this->shopUrl   . '/shop-sitemap.xml')
            ->add($this->ssh . $this->blogUrl   . '/blog-sitemap.xml')
            ->add($this->ssh . $this->summitUrl . '/summit-sitemap.xml')
            ->writeToFile(public_path('sitemap.xml'));
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    private function articlePriority(string $segment): float
    {
        return match ($segment) {
            'outdoor', 'mountaineering', 'indoor', 'ice', 'special_article' => 1.0,
            'other', 'spot_projects'                                         => 0.9,
            'partner'                                                        => 0.7,
            default                                                          => 0.7,
        };
    }
}

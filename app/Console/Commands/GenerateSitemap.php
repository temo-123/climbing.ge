<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

use App\Models\Guide\Article;

use App\Models\Shop\Product;
use App\Models\Shop\Service;
use App\Models\Shop\Tour;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->generate_general_sitemap();

        $this->generate_guide_sitemap();
        $this->generate_shop_sitemap();
    }

    private function generate_general_sitemap() {
        $sitemap = Sitemap::create();

        $sitemap = $this->guide_generator($sitemap, '');
        $sitemap = $this->guide_generator($sitemap, 'ka/');

        $sitemap = $this->shop_generatoe($sitemap, '');
        $sitemap = $this->shop_generatoe($sitemap, 'ka/');

        $this->generate_sitemap($sitemap, 'sitemap.xml');
    }

    private function generate_guide_sitemap() {
        $guide_sitemap = Sitemap::create();

        $guide_sitemap = $this->guide_generator($guide_sitemap, '');
        $guide_sitemap = $this->guide_generator($guide_sitemap, 'ka/');

        $this->generate_sitemap($guide_sitemap, 'guide-sitemap.xml');
    }

    private function generate_shop_sitemap() {
        $shop_sitemap = Sitemap::create();

        $shop_sitemap = $this->shop_generatoe($shop_sitemap, '');
        $shop_sitemap = $this->shop_generatoe($shop_sitemap, 'ka/');

        $this->generate_sitemap($shop_sitemap, 'shop-sitemap.xml');
    }

    private function shop_generatoe($shop_sitemap, $leng) {
        Product::where('published', '=', 1)->get()->each(function (Product $product) use ($shop_sitemap, $leng) {
            $shop_sitemap->add(
                    Url::create(env('APP_SSH') . env('SHOP_URL') . '/' . $leng . 'product/' . $product->url_title)
                    ->setPriority(0.9)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            );
        });
        Service::where("published", "=", 1)->get()->each(function (Service $service) use ($shop_sitemap, $leng) {
            $shop_sitemap->add(
                    Url::create(env('APP_SSH') . env('SHOP_URL') . '/' . $leng . 'service/' . $service->url_title)
                    ->setPriority(0.7)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            );
        });
        Tour::where("published", "=", 1)->get()->each(function (Tour $tour) use ($shop_sitemap, $leng) {
            $shop_sitemap->add(
                    Url::create(env('APP_SSH') . env('SHOP_URL') . '/' . $leng . 'tour/' . $tour->url_title)
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            );
        });

        return $shop_sitemap;
    }

    private function guide_generator($guide_sitemap, $leng) {
        Article::where('category', '=', 'mount_route')->where("published", "=", 1)->get()->each(function (Article $mount_article) use ($guide_sitemap, $leng) {
            $guide_sitemap->add(
                Url::create("/" . $leng . "mountaineering/{$mount_article->url_title}")
                    ->setPriority(1.0)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            );
        });
        Article::where('category', '=', 'outdoor')->where("published", "=", 1)->get()->each(function (Article $outdoor_article) use ($guide_sitemap, $leng) {
            $guide_sitemap->add(
                Url::create("/" . $leng . "outdoor/{$outdoor_article->url_title}")
                    ->setPriority(1.0)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            );
        });
        Article::where('category', '=', 'indoor')->where("published", "=", 1)->get()->each(function (Article $indoor_article) use ($guide_sitemap, $leng) {
            $guide_sitemap->add(
                Url::create("/" . $leng . "indoor/{$indoor_article->url_title}")
                    ->setPriority(1.0)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            );
        });
        Article::where('category', '=', 'ice')->where("published", "=", 1)->get()->each(function (Article $ice_article) use ($guide_sitemap, $leng) {
            $guide_sitemap->add(
                Url::create("/" . $leng . "ice/{$ice_article->url_title}")
                    ->setPriority(1.0)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            );
        });
        Article::where('category', '=', 'other')->where("published", "=", 1)->get()->each(function (Article $other_article) use ($guide_sitemap, $leng) {
            $guide_sitemap->add(
                Url::create("/" . $leng . "other/{$other_article->url_title}")
                    ->setPriority(0.9)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            );
        });
        Article::where('category', '=', 'news')->where("published", "=", 1)->get()->each(function (Article $news_article) use ($guide_sitemap, $leng) {
            $guide_sitemap->add(
                Url::create("/" . $leng . "news/{$news_article->url_title}")
                    ->setPriority(0.7)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            );
        });
        Article::where('category', '=', 'special')->where("published", "=", 1)->get()->each(function (Article $special_article) use ($guide_sitemap, $leng) {
            $guide_sitemap->add(
                Url::create("/" . $leng . "special/{$special_article->url_title}")
                    ->setPriority(1.0)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            );
        });

        return $guide_sitemap;
    }

    private function generate_sitemap($sitemap, $filename) {
        $sitemap->writeToFile(public_path($filename));
    }
}

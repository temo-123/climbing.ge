<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guide\Header_image;
use App\Models\Guide\Article_image;

use App\Models\Guide\Article;
use App\Models\Guide\Locale_article;

use App\Models\Guide\Event;
// use App\Models\Guide\Ru_article;

use App\Models\Guide\Mount;

use App\Models\Site;
use App\Models\Locale_site;
use App\Services\SiteDataService;

use App\Models\Guide\Sector;
use App\Models\Guide\Route;
use App\Models\Guide\Mtp;
use App\Models\Guide\Mtp_pitch;

use App\Models\User;
use App\Models\User\Following_users;
use App\Models\User\Role;
use App\Models\User\Permission;

use App\Models\Shop\Product;
use App\Models\Shop\Product_category;

use App\Models\Social_account;

use App\Models\User\Service_follower;
use App\Models\Guide\Article_comment_complaint;

use App\Models\Guide\Comment;
use App\Models\Shop\Product_feedback;

use App\Models\Guide\Region;

use Illuminate\Support\Facades\Validator;

class SiteDataController extends Controller
{
    public function site_data_counts()
    {
        $counts = [];

        $counts['index_header_images'] = Header_image::count();
        $counts['gallery_images'] = Article_image::count();

        $counts['mount_masives'] = Mount::count();
        $counts['mountaineering_route'] = Article::where("category", "=", 'mount_route')->count();

        $counts['outdoor_climbing'] = Article::where("category", "=", 'outdoor')->count();

        $counts['ice_climbing'] = Article::where("category", "=", 'ice')->count();
        $counts['indoor_gyms'] = Article::where("category", "=", 'indoor')->count();
        $counts['other_antyvity'] = Article::where("category", "=", 'other')->count();
        $counts['news'] = Article::where("category", "=", 'news')->count();
        $counts['techtip'] = Article::where("category", "=", 'techtip')->count();

        $counts['active_events_count'] = Event::where("category", "=", 'event')->where('end_data', '>=', now())->count();
        $counts['completed_events_count'] = Event::where("category", "=", 'event')->where('end_data', '<', now())->count();
        $counts['active_comprtitions_count'] = Event::where("category", "=", "competition")->where('end_data', '>=', now())->count();
        $counts['completed_comprtitions_count'] = Event::where("category", "=", "competition")->where('end_data', '<', now())->count();

        $counts['region'] = Region::count();

        $counts['global_articles_count'] = Article::count();
        $counts['ka_articles_count'] = Locale_article::where("locale", "=", 'ka')->count();
        // $counts['ru_articles_count'] = Locale_article::where("locale", "=", 'ru')->count();
        $counts['us_articles_count'] = Locale_article::where("locale", "=", 'us')->count();

        $counts['bouldering_routes_count'] = Route::where("category", "=", 'bouldering')->count();
        $counts['sport_climbing_routes_count'] = Route::where("category", "=", 'sport')->count();
        $counts['top_rope_routes_count'] = Route::where("category", "=", 'top')->count();
        $counts['tred_routes_count'] = Route::where("category", "=", 'tred')->count();
        $counts['aid_routes_count'] = Route::where("category", "=", 'aid')->count();

        $counts['routes_count'] = Route::count();
        $counts['sectors_count'] = Sector::count();
        $counts['mtp_count'] = Mtp::count();
        $counts['mtp_pitch_count'] = Mtp_pitch::count();

        $counts['article_comment_complaint_count'] = Article_comment_complaint::count();
        // $counts['mtp_pitch_count'] = Mtp_pitch::count();
        
        $counts['article_comments_count'] = Comment::count();
        $counts['product_comments_count'] = Product_feedback::count();

        $counts['users'] = User::count();
        $counts['following_users'] = Following_users::count();

        $counts['guid_follovers'] = Service_follower::where("service", "=", 'guide')->count();
        $counts['shop_follovers'] = Service_follower::where("service", "=", 'shop')->count();
        
        $counts['google_accounts_count'] = Social_account::where('provider', 'google')->count();
        $counts['facebook_accounts_count'] = Social_account::where('provider', 'facebook')->count();

        $counts['roles'] = Role::count();
        $counts['permissions'] = Permission::count();

        $counts['products'] = Product::count();
        $counts['product_categories'] = Product_category::count();

        $counts['us_article_errors'] = $this->getOrphanedLocaleArticles('us')->count();
        $counts['ka_article_errors'] = $this->getOrphanedLocaleArticles('ka')->count();

        return $counts;
    }

    /**
     * Locale articles ('us'/'ka') whose linked global article is missing.
     */
    private function getOrphanedLocaleArticles(string $locale)
    {
        $relation = $locale === 'ka' ? 'global_article_ka' : 'global_article_us';

        return Locale_article::where('locale', $locale)
            ->with($relation)
            ->get()
            ->reject(fn ($local_article) => $local_article->{$relation})
            ->values();
    }

    public function fix_article_bugs(Request $request)
    {
        $orphaned_us = $this->getOrphanedLocaleArticles('us');
        $orphaned_ka = $this->getOrphanedLocaleArticles('ka');

        Locale_article::destroy($orphaned_us->pluck('id'));
        Locale_article::destroy($orphaned_ka->pluck('id'));

        return response()->json([
            'message' => 'Article bugs fixed successfully',
            'us_deleted' => $orphaned_us->count(),
            'ka_deleted' => $orphaned_ka->count(),
        ]);
    }

    public function get_article_errors(Request $request, $locale)
    {
        $allowed = ['us', 'ka'];
        if (!in_array($locale, $allowed)) {
            return response()->json([], 400);
        }

        $errors = $this->getOrphanedLocaleArticles($locale)->map(fn ($local_article) => [
            'id'    => $local_article->id,
            'title' => $local_article->title ?: '(no title)',
            'locale' => $locale,
        ]);

        return response()->json($errors);
    }

    public function get_site_global_data(){
        return SiteDataService::getSiteData()['global_data'] ?? null;
    }

    public function edit_site_global_data(Request $request){
        $data = ['global_data' => $request->site_global_info];
        SiteDataService::updateSiteGlobalData($data);

        return response()->json(['message' => 'Global site data updated successfully']);
    }

    function get_site_locale_data_for_site(Request $request, $lang) {
        // Map locale codes to locale keys
        $localeMap = [
            'en' => 'us',
            'ka' => 'ka',
            'us' => 'us',
        ];

        // Get the actual locale key, default to 'us' for unknown locales
        $localeKey = $localeMap[$lang] ?? 'us';

        // Get global data
        $globalData = Site::first();

        // Get all locale data grouped by locale using the service
        $allLocaleData = \App\Services\Abstract\LocaleSiteService::getGroupedByLocale();
        
        // Get the locale data for the requested locale
        $localeData = $allLocaleData[$localeKey] ?? ($allLocaleData['us'] ?? null);

        return response()->json([
            'locale_data' => $localeData,
            'global_data' => $globalData
        ]);
    }

    public function get_site_locale_data(Request $request, $lang)
    {
        // Map locale codes to database column names
        $localeMap = [
            'en' => 'us_data',
            'ka' => 'ka_data',
            'us' => 'us_data',
        ];

        // Get the actual column name, default to 'us_data' for unknown locales
        // (locale_sites only ever has ka_data/us_data columns, so no schema lookup is needed)
        $columnName = $localeMap[$lang] ?? 'us_data';

        return Locale_site::select('id', 'slug', $columnName)->get();
    }

    /**
     * Add new site locale data
     */
    public function add_site_local_data(Request $request)
    {
        $data = $request->data;
        
        // Validate the data
        $validator = Validator::make($data, [
            'slug' => 'required|string|max:255|unique:locale_sites,slug',
            'ka_data' => 'nullable|string',
            'us_data' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation' => $validator->messages()], 422);
        }

        $localeSite = new Locale_site();
        $localeSite->slug = $data['slug'];
        $localeSite->ka_data = $data['ka_data'] ?? '';
        $localeSite->us_data = $data['us_data'] ?? '';
        $localeSite->save();

        return response()->json(['message' => 'Site locale data added successfully'], 200);
    }

    /**
     * Edit existing site locale data
     */
    public function edit_site_local_data(Request $request, $id, $language)
    {
        $data = $request->data;
        
        // Validate the data
        $validator = Validator::make($data, [
            'ka_data' => 'nullable|string',
            'us_data' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation' => $validator->messages()], 422);
        }

        try {
            // Find the locale site record
            $localeSite = Locale_site::findOrFail($id);
            
            // Update the appropriate language data
            if ($language === 'ka') {
                $localeSite->ka_data = $data['ka_data'] ?? '';
            } elseif ($language === 'us') {
                $localeSite->us_data = $data['us_data'] ?? '';
            } else {
                return response()->json(['error' => 'Invalid language specified'], 400);
            }
            
            $localeSite->save();

            return response()->json(['message' => 'Site locale data updated successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Locale site data not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update site locale data'], 500);
        }
    }
}

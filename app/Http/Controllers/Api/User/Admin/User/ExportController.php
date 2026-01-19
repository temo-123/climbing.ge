<?php

namespace App\Http\Controllers\Api\User\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guide\Article;
use App\Models\Guide\Sector;
use App\Models\Guide\Route;
use App\Models\Guide\Mtp;
use App\Models\Guide\Locale_article;
use App\Services\ArticlesService;
use App\Services\PermissionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class ExportController extends Controller
{
    public function getUniqueCategories()
    {
        $categories = Article::distinct()->pluck('category')->filter()->values();
        return response()->json($categories);
    }

    public function getArticlesByCategory(Request $request, $category)
    {
        $articles = Article::where('category', $category)->get();
        return response()->json($articles);
    }

    public function exportArticlesPdf(Request $request)
    {
        $auth = PermissionService::authorize('export', 'show');
        if ($auth) return $auth;
        
        $articleIds = $request->input('article_ids');
        $exportSectors = $request->input('export_sectors', false);
        $locale = $request->input('locale', 'ka');

        $articles = Article::whereIn('id', $articleIds)->get();

        if ($articles->isEmpty()) {
            return response()->json(['message' => 'No articles found'], 404);
        }

        // Get localized articles
        $localizedArticles = [];
        
        if ($locale === 'all') {
            $locales = ['ka', 'us'];
            foreach ($locales as $currentLocale) {
                $localeData = ArticlesService::get_locale_article_use_locale($articles, $currentLocale);
                foreach ($localeData as &$article) {
                    $article['locale'] = $currentLocale;
                    // Ensure proper data structure
                    if (!isset($article['global_data']) || !$article['global_data']) {
                        $article['global_data'] = $article;
                    }
                    if (!isset($article['locale_data'])) {
                        $article['locale_data'] = null;
                    }
                }
                $localizedArticles = array_merge($localizedArticles, $localeData);
            }
        } else {
            $localizedArticles = ArticlesService::get_locale_article_use_locale($articles, $locale);
            foreach ($localizedArticles as &$article) {
                $article['locale'] = $locale;
                // Ensure proper data structure
                if (!isset($article['global_data']) || !$article['global_data']) {
                    $article['global_data'] = $article;
                }
                if (!isset($article['locale_data'])) {
                    $article['locale_data'] = null;
                }
            }
        }

        // If still no data, create basic structure
        if (empty($localizedArticles)) {
            foreach ($articles as $article) {
                $localizedArticles[] = [
                    'locale_data' => null,
                    'global_data' => $article,
                    'locale' => $locale
                ];
            }
        }

        // Prepare translations
        $translations = [];
        $availableLocales = $locale === 'all' ? ['ka', 'us'] : [$locale];
        
        foreach ($availableLocales as $transLocale) {
            $langFile = $transLocale === 'us' ? 'en' : $transLocale;
            $translationFile = resource_path('lang/' . $langFile . '.json');
            
            if (file_exists($translationFile)) {
                $langData = json_decode(file_get_contents($translationFile), true);
                
                $translations[$transLocale] = [
                    'export_title' => $langData['guide']['title']['export'] ?? 'Export',
                    'description' => $langData['guide']['article']['title']['description'] ?? 'Description',
                    'route' => $langData['guide']['article']['title']['route'] ?? 'Routes',
                    'how_get' => $langData['guide']['article']['title']['how get'] ?? 'How to Get There',
                    'best_time' => $langData['guide']['article']['title']['best time'] ?? 'Best Time to Climb',
                    'what_need' => $langData['guide']['article']['title']['what need'] ?? 'What You Need',
                    'info' => $langData['guide']['article']['title']['info'] ?? 'Info',
                    'price' => $langData['guide']['article']['title']['price'] ?? 'Price',
                    'address' => $langData['guide']['article']['title']['address'] ?? 'Address',
                    'url_title' => $langData['guide']['article']['title']['url_title'] ?? 'URL Title',
                    'category' => $langData['guide']['article']['title']['category'] ?? 'Category',
                    'published' => $langData['guide']['article']['title']['published'] ?? 'Published',
                    'yes' => $langData['guide']['article']['title']['yes'] ?? 'Yes',
                    'no' => $langData['guide']['article']['title']['no'] ?? 'No',
                    'sectors' => $langData['guide']['article']['title']['sectors'] ?? 'Sectors',
                ];
            } else {
                // Fallback translations
                $translations[$transLocale] = [
                    'export_title' => 'Export',
                    'description' => 'Description',
                    'route' => 'Routes',
                    'how_get' => 'How to Get There',
                    'best_time' => 'Best Time to Climb',
                    'what_need' => 'What You Need',
                    'info' => 'Info',
                    'price' => 'Price',
                    'address' => 'Address',
                    'url_title' => 'URL Title',
                    'category' => 'Category',
                    'published' => 'Published',
                    'yes' => 'Yes',
                    'no' => 'No',
                    'sectors' => 'Sectors',
                ];
            }
        }

        $data = [
            'localized_articles' => $localizedArticles, 
            'locale' => $locale, 
            'translations' => $translations
        ];

        // Add sectors data if requested
        if ($exportSectors) {
            $sectorsData = [];
            foreach ($articles as $article) {
                if (in_array($article->category, ['outdoor', 'ice'])) {
                    $sectors = $article->sectors()->with(['images', 'sport_routes', 'boulder_routes', 'mtps.pitchs'])->get();
                    
                    foreach ($sectors as $sector) {
                        $sectorRoutes = [];
                        
                        // Get sport routes
                        foreach ($sector->sport_routes as $route) {
                            $sectorRoutes[] = [
                                'type' => 'sport',
                                'title' => $route->title,
                                'grade' => $route->grade,
                                'or_grade' => $route->or_grade,
                                'height' => $route->height,
                                'bolts' => $route->bolts,
                                'anchor_type' => $route->anchor_type,
                                'author' => $route->author,
                                'text' => $route->text,
                            ];
                        }
                        
                        // Get boulder routes
                        foreach ($sector->boulder_routes as $route) {
                            $sectorRoutes[] = [
                                'type' => 'boulder',
                                'title' => $route->title,
                                'grade' => $route->grade,
                                'or_grade' => $route->or_grade,
                                'height' => $route->height,
                                'bolts' => $route->bolts,
                                'anchor_type' => $route->anchor_type,
                                'author' => $route->author,
                                'text' => $route->text,
                            ];
                        }
                        
                        // Get MTPs
                        $sectorMtps = [];
                        foreach ($sector->mtps as $mtp) {
                            $mtpPitchs = [];

                            foreach ($mtp->pitchs as $pitch) {
                                $mtpPitchs[] = [
                                    'num' => $pitch->num,
                                    'grade' => $pitch->grade,
                                    'height' => $pitch->height,
                                    'description' => $pitch->text,
                                ];
                            }
                            
                            $sectorMtps[] = [
                                'title' => $mtp->title,
                                'text' => $mtp->text,
                                'last_carabin' => $mtp->last_carabin,
                                'height' => $mtp->height,
                                'pitchs' => $mtpPitchs,
                            ];
                        }
                        
                        $sectorsData[] = [
                            'article' => [
                                'title' => $article->url_title,
                                'category' => $article->category,
                            ],
                            'sector' => [
                                'name' => $sector->name,
                                'text' => $sector->text,
                                'all_day_in_shade' => $sector->all_day_in_shade,
                                'all_day_in_sun' => $sector->all_day_in_sun,
                                'in_the_shade_afternoon' => $sector->in_the_shade_afternoon,
                                'in_the_shade_befornoon' => $sector->in_the_shade_befornoon,
                                'in_shade_after_10' => $sector->in_shade_after_10,
                                'in_shade_after_15' => $sector->in_shade_after_15,
                                'slabby' => $sector->slabby,
                                'vertical' => $sector->vertical,
                                'overhang' => $sector->overhang,
                                'roof' => $sector->roof,
                                'for_family' => $sector->for_family,
                                'for_kids' => $sector->for_kids,
                                'wolking_time' => $sector->wolking_time,
                                'is_helmet' => $sector->is_helmet,
                            ],
                            'images' => $sector->images,
                            'routes' => $sectorRoutes,
                            'mtps' => $sectorMtps,
                        ];
                    }
                }
            }
            
            $data['sectors_data'] = $sectorsData;
        }

        $pdf = Pdf::loadView('pdf.articles', $data);

        // Create user-friendly filename
        $urlTitle = $articles->first()->url_title ?: 'articles';
        if ($locale === 'all') {
            $filename = Str::slug($urlTitle) . ' (ka,us).pdf';
        } else {
            $filename = Str::slug($urlTitle) . ' (' . $locale . ').pdf';
        }

        return $pdf->download($filename);
    }
}


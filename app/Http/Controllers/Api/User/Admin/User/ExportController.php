<?php

namespace App\Http\Controllers\Api\User\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guide\Article;
use App\Models\Guide\Sector;
use App\Models\Guide\Route;
use App\Models\Guide\Mtp;
use App\Models\Guide\Locale_article;
use App\Models\Guide\Mtp_pitch;
use App\Services\ArticlesService;
use App\Services\PermissionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ExportController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // GradeChartService removed - no grade conversion needed
    }

    /**
     * Get list of unique article categories
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUniqueCategories()
    {
        $auth = PermissionService::authorize('export', 'show');
        if ($auth) return $auth;

        $categories = Article::distinct()->pluck('category')->filter()->values();
        return response()->json($categories);
    }

    /**
     * Get articles by category
     *
     * @param Request $request
     * @param string $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArticlesByCategory(Request $request, $category)
    {
        $auth = PermissionService::authorize('export', 'show');
        if ($auth) return $auth;

        // titles in both languages + region and sector count, for the export page list
        $articles = Article::where('category', $category)
            ->with('outdoor_region:id,us_name')
            ->withCount('sectors')
            ->orderByDesc('updated_at')
            ->get(['id', 'category', 'url_title', 'published', 'us_article_id', 'ka_article_id', 'created_at', 'updated_at']);
        $titles = Locale_article::whereIn('id', $articles->pluck('us_article_id')->merge($articles->pluck('ka_article_id'))->filter())
            ->pluck('title', 'id');

        return response()->json($articles->map(fn ($a) => array_merge($a->only(['id', 'category', 'url_title', 'published', 'created_at', 'updated_at', 'sectors_count']), [
            'us_title' => $titles[$a->us_article_id] ?? null,
            'ka_title' => $titles[$a->ka_article_id] ?? null,
            'region'   => optional($a->outdoor_region->first())->us_name,
        ])));
    }

    /**
     * CSV with one row per selected article: id, category, titles, public
     * URL, published, region, sector / route / multi-pitch counts, dates.
     */
    public function exportArticlesCsv(Request $request)
    {
        $auth = PermissionService::authorize('export', 'show');
        if ($auth) return $auth;

        $request->validate(['article_ids' => 'required|array', 'article_ids.*' => 'integer']);

        $articles = Article::whereIn('id', $request->article_ids)->with('outdoor_region:id,us_name')->get();
        $titles = Locale_article::whereIn('id', $articles->pluck('us_article_id')->merge($articles->pluck('ka_article_id'))->filter())->pluck('title', 'id');
        $sector_ids = Sector::whereIn('article_id', $articles->pluck('id'))->get(['id', 'article_id']);

        $rows = [['id', 'category', 'title_en', 'title_ka', 'url', 'published', 'region', 'sectors', 'routes', 'multi_pitch', 'created_at', 'updated_at']];
        foreach ($articles as $a) {
            $own = $sector_ids->where('article_id', $a->id)->pluck('id');
            $rows[] = [
                $a->id, $a->category, $titles[$a->us_article_id] ?? '', $titles[$a->ka_article_id] ?? '',
                $this->publicUrl($a), $a->published ? 'yes' : 'no', optional($a->outdoor_region->first())->us_name,
                $own->count(), Route::whereIn('sector_id', $own)->count(), Mtp::whereIn('sector_id', $own)->count(),
                optional($a->created_at)->toDateTimeString(), optional($a->updated_at)->toDateTimeString(),
            ];
        }

        return $this->csvResponse($rows, 'articles_' . date('Y-m-d') . '.csv');
    }

    /**
     * CSV with every route of the selected areas: area, sector, number,
     * name, type, grade (+ alternative), bolts, height, author, first ascent,
     * and every multi-pitch pitch as its own row.
     */
    public function exportRoutesCsv(Request $request)
    {
        $auth = PermissionService::authorize('export', 'show');
        if ($auth) return $auth;

        $request->validate(['article_ids' => 'required|array', 'article_ids.*' => 'integer']);

        $articles = Article::whereIn('id', $request->article_ids)->get(['id', 'url_title', 'us_article_id']);
        $titles = Locale_article::whereIn('id', $articles->pluck('us_article_id')->filter())->pluck('title', 'id');

        $rows = [['area', 'sector', 'sector_published', 'num', 'name', 'type', 'grade', 'alt_grade', 'bolts', 'height', 'author', 'first_ascent', 'multi_pitch']];
        foreach ($articles as $article) {
            $area = $titles[$article->us_article_id] ?? $article->url_title;
            foreach (Sector::where('article_id', $article->id)->orderBy('num')->get() as $sector) {
                foreach (Route::where('sector_id', $sector->id)->orderBy('num')->get() as $r) {
                    $rows[] = [$area, $sector->name, $sector->published ? 'yes' : 'no', $r->num, $r->name, $r->category, $r->grade, $r->or_grade, $r->bolts, $r->height, $r->author, $r->first_ascent, ''];
                }
                foreach (Mtp::where('sector_id', $sector->id)->orderBy('num')->get() as $mtp) {
                    foreach (Mtp_pitch::where('mtp_id', $mtp->id)->orderBy('num')->get() as $p) {
                        $rows[] = [$area, $sector->name, $sector->published ? 'yes' : 'no', $p->num, $p->name, $p->category, $p->grade, $p->or_grade, $p->bolts, $p->height, '', '', $mtp->name];
                    }
                }
            }
        }

        return $this->csvResponse($rows, 'routes_' . date('Y-m-d') . '.csv');
    }

    // UTF-8 with BOM so Excel opens Georgian text correctly
    private function csvResponse(array $rows, string $filename)
    {
        $out = fopen('php://temp', 'r+');
        fwrite($out, "\xEF\xBB\xBF");
        foreach ($rows as $row) fputcsv($out, array_map(fn ($v) => $v === null ? '' : $v, $row));
        rewind($out);
        $csv = stream_get_contents($out);
        fclose($out);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function publicUrl(Article $a): string
    {
        $paths = ['outdoor' => 'outdoor', 'ice' => 'ice', 'indoor' => 'indoor', 'mount_route' => 'mountaineering', 'other' => 'other',
                  'tech_tip' => 'tech_tip', 'news' => 'news', 'partners' => 'partner', 'spot_projects' => 'spot_project', 'special' => 'special_article'];
        return 'https://' . config('app.site_url') . '/' . ($paths[$a->category] ?? $a->category) . '/' . $a->url_title;
    }

    /**
     * Export articles to PDF
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportArticlesPdf(Request $request)
    {
        $auth = PermissionService::authorize('export', 'show');
        if ($auth) return $auth;

        try {
            // Get parameters
            $articleIds = $request->input('article_ids');
            $exportSectors = $request->input('export_sectors', false);
            $locale = $request->input('locale', 'ka');

            // Validate required parameters
            if (empty($articleIds)) {
                return response()->json(['message' => 'No articles selected'], 400);
            }

            // Get articles
            $articles = Article::whereIn('id', $articleIds)->get();

            if ($articles->isEmpty()) {
                return response()->json(['message' => 'No articles found'], 404);
            }

            // Get localized articles
            $localizedArticles = $this->getLocalizedArticles($articles, $locale);

            // Prepare translations
            $translations = $this->prepareTranslations($locale);

            // Build data for PDF
            $urlTitle = $articles->first()->url_title ?: 'articles';
            $date = date('d-m-Y');
            if ($locale === 'all') {
                $localeStr = 'us + ka';
            } else {
                $localeStr = $locale;
            }
            $pdfTitle = $urlTitle . ' (' . $localeStr . ', ' . $date . ')';
            
            $data = [
                'localized_articles' => $localizedArticles,
                'locale' => $locale,
                'translations' => $translations,
                'pdf_title' => $pdfTitle,
            ];

            // Add sectors data if requested
            if ($exportSectors) {
                $data['sectors_data'] = $this->getAllArticleSectors(
                    $articles->toArray(),
                    $locale
                );
            }

            // Generate PDF
            $pdf = Pdf::loadView('pdf.articles', $data);

            // Create user-friendly filename
            $date = date('d-m-Y');
            if ($locale === 'all') {
                $localeStr = 'us + ka';
            } else {
                $localeStr = $locale;
            }
            $filename = $urlTitle . ' (' . $localeStr . ', ' . $date . ').pdf';

            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('PDF Export Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Error generating PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get localized articles with their content
     *
     * @param \Illuminate\Support\Collection $articles
     * @param string $locale
     * @return array
     */
    protected function getLocalizedArticles($articles, string $locale): array
    {
        $localizedArticles = [];

        if ($locale === 'all') {
            $locales = ['ka', 'us'];
            foreach ($locales as $currentLocale) {
                $localeData = ArticlesService::get_locale_article_use_locale($articles, $currentLocale);
                
                foreach ($localeData as $article) {
                    $localizedArticles[] = $this->buildArticleStructure($article, $currentLocale);
                }
            }
        } else {
            $localeData = ArticlesService::get_locale_article_use_locale($articles, $locale);
            
            foreach ($localeData as $article) {
                $localizedArticles[] = $this->buildArticleStructure($article, $locale);
            }
        }

        // If still no data, create basic structure from global articles
        if (empty($localizedArticles)) {
            foreach ($articles as $article) {
                $localizedArticles[] = [
                    'locale_data' => null,
                    'global_data' => $article,
                    'locale' => $locale
                ];
            }
        }

        return $localizedArticles;
    }

    /**
     * Build article structure from locale data
     *
     * @param array $article
     * @param string $locale
     * @return array
     */
    protected function buildArticleStructure(array $article, string $locale): array
    {
        return [
            'locale_data' => $article['locale_data'] ?? null,
            'global_data' => $article['global_data'] ?? null,
            'locale' => $locale
        ];
    }

    /**
     * Prepare translations for PDF
     *
     * @param string $locale
     * @return array
     */
    protected function prepareTranslations(string $locale): array
    {
        $translations = [];
        $availableLocales = $locale === 'all' ? ['ka', 'us'] : [$locale];

        foreach ($availableLocales as $transLocale) {
            $langFile = $transLocale === 'us' ? 'en' : $transLocale;
            $translationFile = resource_path('lang/' . $langFile . '.json');

            $translations[$transLocale] = $this->loadTranslationFile($translationFile, $transLocale);
        }

        return $translations;
    }

    /**
     * Load translation file
     *
     * @param string $filePath
     * @param string $locale
     * @return array
     */
    protected function loadTranslationFile(string $filePath, string $locale): array
    {
        if (file_exists($filePath)) {
            $langData = json_decode(file_get_contents($filePath), true);

            return [
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
        }

        // Fallback translations
        return [
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

    /**
     * Generate filename for PDF
     *
     * @param string $urlTitle
     * @param string $locale
     * @return string
     */
    protected function generateFilename(string $urlTitle, string $locale): string
    {
        if ($locale === 'all') {
            return Str::slug($urlTitle) . ' (ka,us).pdf';
        }
        return Str::slug($urlTitle) . ' (' . $locale . ').pdf';
    }

    /**
     * Get sectors for an article with full localization
     *
     * @param Article $article The article
     * @param string $locale The locale (ka, us)
     * @return array Array of sectors with localized data
     */
    public function getArticleSectors(Article $article, string $locale = 'ka'): array
    {
        $sectors = $article->sectors()
            ->with(['images', 'routes', 'mtps.pitchs', 'sector_local_image'])
            ->get();

        $localizedSectors = [];

        foreach ($sectors as $sector) {
            $localizedSectors[] = $this->getLocalizedSector($sector, $locale);
        }

        return $localizedSectors;
    }

    /**
     * Get a localized sector with all its data
     *
     * @param Sector $sector The sector model
     * @param string $locale The locale
     * @return array Localized sector data
     */
    public function getLocalizedSector(Sector $sector, string $locale = 'ka'): array
    {
        // Get localized content based on locale
        $localePrefix = $locale === 'us' ? 'us' : 'ka';
        $descriptionField = $localePrefix . '_description';

        return [
            'id' => $sector->id,
            'name' => $sector->name,
            'text' => $sector->$descriptionField ?: $sector->text,
            'original_text' => $sector->text,
            'characteristics' => $this->getSectorCharacteristics($sector),
            // Include raw fields for icon display in PDF
            'in_shade_after_10' => $sector->in_shade_after_10,
            'in_shade_after_15' => $sector->in_shade_after_15,
            'in_the_shade_befornoon' => $sector->in_the_shade_befornoon,
            'in_the_shade_afternoon' => $sector->in_the_shade_afternoon,
            'all_day_in_shade' => $sector->all_day_in_shade,
            'all_day_in_sun' => $sector->all_day_in_sun,
            'roof' => $sector->roof,
            'overhang' => $sector->overhang,
            'vertical' => $sector->vertical,
            'slabby' => $sector->slabby,
            'for_family' => $sector->for_family,
            'for_kids' => $sector->for_kids,
            'wolking_time' => $sector->wolking_time,
            'is_helmet' => $sector->is_helmet,
            'images' => $this->getSectorImages($sector),
            'routes' => $this->getSectorRoutes($sector),
            'mtps' => $this->getSectorMtps($sector, $locale),
        ];
    }

    /**
     * Get sector characteristics as array
     *
     * @param Sector $sector
     * @return array
     */
    protected function getSectorCharacteristics(Sector $sector): array
    {
        $characteristics = [];

        // Sun/Shade characteristics
        if ($sector->all_day_in_shade) {
            $characteristics['sun'] = 'All day in shade';
        }
        if ($sector->all_day_in_sun) {
            $characteristics['sun'] = 'All day in sun';
        }
        if ($sector->in_the_shade_afternoon) {
            $characteristics['shade_afternoon'] = 'In shade in afternoon';
        }
        if ($sector->in_the_shade_befornoon) {
            $characteristics['shade_morning'] = 'In shade in morning';
        }
        if ($sector->in_shade_after_10) {
            $characteristics['shade_after_10'] = 'In shade after 10:00';
        }
        if ($sector->in_shade_after_15) {
            $characteristics['shade_after_15'] = 'In shade after 15:00';
        }

        // Angle characteristics
        if ($sector->slabby) {
            $characteristics['angle'][] = 'Slabby';
        }
        if ($sector->vertical) {
            $characteristics['angle'][] = 'Vertical';
        }
        if ($sector->overhang) {
            $characteristics['angle'][] = 'Overhang';
        }
        if ($sector->roof) {
            $characteristics['angle'][] = 'Roof';
        }

        // Family-friendly
        if ($sector->for_family) {
            $characteristics['family'] = 'For family';
        }
        if ($sector->for_kids) {
            $characteristics['kids'] = 'For kids';
        }
        if ($sector->wolking_time) {
            $characteristics['walking_time'] = $sector->wolking_time;
        }
        if ($sector->is_helmet) {
            $characteristics['helmet'] = 'Helmet required';
        }

        return $characteristics;
    }

    /**
     * Get sector images (both regular and local images)
     *
     * @param Sector $sector
     * @return array
     */
    protected function getSectorImages(Sector $sector): array
    {
        $images = [];

        // Get regular sector images
        $regularImages = $sector->images()->get();
        foreach ($regularImages as $image) {
            $images[] = [
                'id' => $image->id,
                'image' => $image->image,
                'title' => $image->title,
                'num' => $image->num,
                'type' => 'regular',
            ];
        }

        // Get sector local images (belongsToMany relationship)
        $localImages = $sector->sector_local_image ?? [];
        foreach ($localImages as $localImg) {
            $images[] = [
                'id' => $localImg->id,
                'image' => $localImg->image,
                'title' => $localImg->title ?? '',
                'num' => $localImg->num ?? 0,
                'type' => 'local',
            ];
        }

        // Sort by num
        usort($images, function($a, $b) {
            return ($a['num'] ?? 0) - ($b['num'] ?? 0);
        });

        return $images;
    }

    /**
     * Get sector routes
     *
     * @param Sector $sector
     * @return array
     */
    protected function getSectorRoutes(Sector $sector): array
    {
        $routes = [];

        // Get all routes loaded with the sector
        $allRoutes = $sector->routes ?? [];

        foreach ($allRoutes as $route) {
            // Determine climbing type based on category
            $climbingType = ($route->category === 'bouldering') ? 'bouldering' : 'lead';
            $routes[] = $this->getLocalizedRoute($route, $climbingType);
        }

        return $routes;
    }

    /**
     * Get a localized route
     *
     * @param Route $route
     * @param string $climbingType
     * @return array
     */
    protected function getLocalizedRoute(Route $route, string $climbingType = 'lead'): array
    {
        return [
            'id' => $route->id,
            'type' => $climbingType,
            'title' => $route->name ?: ($route->title ?? 'Unnamed Route'),
            'category' => $route->category,
            'grade' => $route->grade,
            'or_grade' => $route->or_grade,
            'original_grade' => $route->or_grade,
            'height' => $route->height,
            'bolts' => $route->bolts,
            'anchor_type' => $route->anchor_type,
            'author' => $route->author,
            'text' => $route->text,
        ];
    }

    /**
     * Get sector MTPs with localized content
     *
     * @param Sector $sector
     * @param string $locale
     * @return array
     */
    protected function getSectorMtps(Sector $sector, string $locale = 'ka'): array
    {
        $mtps = [];

        foreach ($sector->mtps as $mtp) {
            $mtpData = [
                'id' => $mtp->id,
                'title' => $mtp->title,
                'text' => $mtp->text,
                'last_carabin' => $mtp->last_carabin,
                'height' => $mtp->height,
                'pitchs' => [],
            ];

            foreach ($mtp->pitchs as $pitch) {
                $mtpData['pitchs'][] = [
                    'id' => $pitch->id,
                    'num' => $pitch->num,
                    'grade' => $pitch->grade,
                    'height' => $pitch->height,
                    'description' => $pitch->text,
                ];
            }

            $mtps[] = $mtpData;
        }

        return $mtps;
    }

    /**
     * Get all sectors with routes for multiple articles
     *
     * @param \Illuminate\Support\Collection|array $articles Collection or array of Article models
     * @param string $locale The locale
     * @return array Array of sectors with article context
     */
    public function getAllArticleSectors($articles, string $locale = 'ka'): array
    {
        $allSectors = [];

        // Convert to collection if it's an array
        $articlesCollection = is_array($articles) ? collect($articles) : $articles;

        foreach ($articlesCollection as $article) {
            // Get category - handle both array and object access
            $category = null;
            if (is_object($article)) {
                $category = $article->category ?? null;
                $urlTitle = $article->url_title ?? '';
                $articleId = $article->id ?? null;
            } elseif (is_array($article)) {
                $category = $article['category'] ?? null;
                $urlTitle = $article['url_title'] ?? '';
                $articleId = $article['id'] ?? null;
            }

            // Only get sectors for outdoor and ice categories
            if (in_array($category, ['outdoor', 'ice'])) {
                // Re-fetch as model to get relations
                $articleModel = Article::find($articleId);
                if ($articleModel) {
                    $sectors = $this->getArticleSectors($articleModel, $locale);

                    foreach ($sectors as $sector) {
                        $allSectors[] = [
                            'article' => [
                                'title' => $urlTitle,
                                'category' => $category,
                            ],
                            'sector' => $sector,
                        ];
                    }
                }
            }
        }

        return $allSectors;
    }
}


<?php

namespace App\Http\Controllers\Api\User\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guide\Article;
use App\Models\Guide\Sector;
use App\Models\Guide\Locale_article;
use App\Services\ArticlesService;
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
        // $request->validate([
        //     'article_ids' => 'required|array',
        //     'article_ids.*' => 'integer|exists:articles,id',
        //     'export_sector' => 'boolean',
        //     'locale' => 'required|string|in:ka,us,ru',
        // ]);

        $articleIds = $request->input('article_ids');
        $exportSectors = $request->input('export_sectors', false);
        $locale = $request->input('locale', 'ka'); // Default to Georgian

        $articles = Article::whereIn('id', $articleIds)->get();

        if ($articles->isEmpty()) {
            return response()->json(['message' => 'No articles found'], 404);
        }

        // Handle "all" locale - export in both ka and us
        if ($locale === 'all') {
            $localizedArticles = [];
            $locales = ['ka', 'us'];

            foreach ($locales as $currentLocale) {
                $localeData = ArticlesService::get_locale_article_use_locale($articles, $currentLocale);
                // Add locale info to each article for view to use correct translations
                foreach ($localeData as &$article) {
                    $article['locale'] = $currentLocale;
                }
                $localizedArticles = array_merge($localizedArticles, $localeData);
            }
        } else {
            // Get localized articles using ArticlesService
            $localizedArticles = ArticlesService::get_locale_article_use_locale($articles, $locale);
            // Add locale info
            foreach ($localizedArticles as &$article) {
                $article['locale'] = $locale;
            }
        }

        // Prepare translations for each locale
        $translations = [];
        $availableLocales = $locale === 'all' ? ['ka', 'us'] : [$locale];
        foreach ($availableLocales as $transLocale) {
            $langFile = $transLocale === 'us' ? 'en' : $transLocale;
            $translationFile = resource_path('lang/' . $langFile . '.json');
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
        }

        $data = ['localized_articles' => $localizedArticles, 'locale' => $locale, 'translations' => $translations];

        if ($exportSectors) {
            $sectors = Sector::all();
            $data['sectors'] = $sectors;
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

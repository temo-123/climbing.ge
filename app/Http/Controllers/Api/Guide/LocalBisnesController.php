<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guide\Article;
use App\Models\Guide\Locale_bisnes;
use App\Models\Guide\Suport_local_bisnes;
use App\Models\Guide\Suport_local_bisnes_image;
use App\Models\Guide\Suport_local_bisnes_article;

// use App\Services\URLTitleService;
use App\Services\Abstract\ImageControllService;
use App\Services\GalleryService;
use App\Services\Abstract\LocaleContentControllService;

use Validator;

class LocalBisnesController extends Controller
{
    public function get_local_bisnes_for_article(Request $request)
    {
        $data = [];

        try {
            // Get the article by URL title
            $article = Article::where('url_title', '=', $request->article_url_title)->first();
            
            if (!$article) {
                return $data; // Return empty array if article not found
            }

            // Get businesses associated with the article that are published
            $article_bisnes_global_data = $article->businesses()->where('published', '=', 1)->take(2)->get();
            
            if ($article_bisnes_global_data && $article_bisnes_global_data->isNotEmpty()) {
                $currentDate = now();
                
                foreach($article_bisnes_global_data as $article_bisne_global_data){
                    $shouldShowBusiness = false;
                    
                    // Check if business should be shown based on publication settings
                    if ($article_bisne_global_data->public_totaly) {
                        // Business is published totally
                        $shouldShowBusiness = true;
                    } else if ($article_bisne_global_data->published_data) {
                        // Check scheduled publication date
                        $publishDate = \Carbon\Carbon::parse($article_bisne_global_data->published_data);
                        $shouldShowBusiness = $publishDate->lte($currentDate);
                    }
                    
                    if ($shouldShowBusiness) {
                        // Get local business data
                        $article_bisnes_local_data = $this->get_article_bisnes_local_data($request->locale == 'ka', $article_bisne_global_data);
                        
                        // Safely get business images
                        $bisnes_images = '';
                        if ($article_bisne_global_data->bisnes_images && $article_bisne_global_data->bisnes_images->isNotEmpty()) {
                            $firstImage = $article_bisne_global_data->bisnes_images->first();
                            $bisnes_images = $firstImage->image ?? '';
                        }

                        $data[] = [
                            'global_data' => $article_bisne_global_data,
                            'local_data' => $article_bisnes_local_data,
                            'image' => $bisnes_images
                        ];
                    }
                }
            }
            
            return $data;
            
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error fetching local businesses for article: ' . $e->getMessage());
            return $data; // Return empty array on error
        }
    }

    private function get_article_bisnes_local_data($lang, $article_bisnes_global_data){
        $article_bisnes_local_data = [];

        if($lang == 'ka'){
            $article_bisnes_local_data = $article_bisnes_global_data->ka_bisnes;
        }
        // else if($lang == 'ru'){
        //     $article_bisnes_local_data = $article_bisnes_global_data->ru_bisnes;
        // }
        else{
            $article_bisnes_local_data = $article_bisnes_global_data->us_bisnes;
        }

        return $article_bisnes_local_data;
    }

    public function get_local_bisnes_in_page(Request $request)
    {
        $article_bisnes_global_data = Suport_local_bisnes::where('url_title', '=', $request->url_title)->first();
        if ($article_bisnes_global_data) {
            if($request->locale == 'ka'){
                $article_bisnes_local_data = $article_bisnes_global_data->ka_bisnes;
            }
            // else if($request->locale == 'ru'){
            //     $article_bisnes_local_data = $article_bisnes_global_data->ru_bisnes;
            // }
            else{
                $article_bisnes_local_data = $article_bisnes_global_data->us_bisnes;
            }
    
            $bisnes_images = $article_bisnes_global_data->bisnes_images;
    
            $data = [
                'global_data' => $article_bisnes_global_data,
                'locale_data' => $article_bisnes_local_data,
                'images' => $bisnes_images
            ];
            return $data;
        }
        // else{
        //     return 'No ';
        // }
    }

    public function get_local_bisneses(Request $request)
    {
        return Suport_local_bisnes::latest('id')->get();
    }

    public function get_bisnes_images(Request $request)
    {
        $bisnes = Suport_local_bisnes::where('id', '=', $request->bisnes_id)->first();

        return $bisnes->bisnes_images;
    }

    public function get_bisnes_article_relation(Request $request)
    {
        $bisnes = Suport_local_bisnes::where('id', '=', $request->bisnes_id)->first();


        return $bisnes->articles;
    }

    public function get_article_categories(Request $request)
    {
        // Get unique categories from articles table
        $categories = Article::whereNotNull('category')
                           ->where('category', '!=', '')
                           ->distinct()
                           ->pluck('category')
                           ->sort()
                           ->values();
        
        return $categories;
    }
    
}

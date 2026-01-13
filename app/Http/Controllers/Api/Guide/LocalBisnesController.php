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

    // public function add_local_bisnes(Request $request)
    // {
    //     $data = json_decode($request->data, true );
    //     $image_path = 'images/local_bisnes_img/';
        
    //     $article_adding = LocaleContentControllService::add_content($data, Suport_local_bisnes::class, Locale_bisnes::class, '_bisnes', $request, $image_path);

    //     if (!array_key_exists('validation', $article_adding->original)) {
    //         // GalleryService::add_gallery_images(
    //         //     $request->gallery_images, 
    //         //     $article_adding->original['global_bisnes_id'], 
    //         //     Article_image::class, 
    //         //     'image', 
    //         //     'article_id', 
    //         //     '/images/local_bisnes_img/'
    //         // );

    //         if($request->hasFile('bisnes_images')){
    //             $this->add_bisnes_images($request['bisnes_images'], $article_adding->original['global_bisnes_id']);
    //         }

    //         if($request->bisnes_new_article_relations){
    //             $this->add_bisnes_relation($request['bisnes_new_article_relations'], $article_adding->original['global_bisnes_id']);
    //         }
    //     }
    //     else {
    //         return $article_adding;
    //     }
    // }

    // public function edit_local_bisnes(Request $request)
    // {
    //     $image_path = 'images/local_bisnes_img/';

    //     $article_editing = LocaleContentControllService::edit_content(Suport_local_bisnes::class, Locale_bisnes::class, '_bisnes', $request, $image_path);

    //     if(!array_key_exists('validation', $article_editing->original)){
    //         if($request->hasFile('bisnes_new_images')){
    //             $this->add_bisnes_images($request['bisnes_new_images'], $article_editing->original['global_bisnes_id']);
    //         }
            
    //         if($request->bisnes_new_article_relations){
    //             $this->add_bisnes_relation($request['bisnes_new_article_relations'], $article_editing->original['global_bisnes_id']);
    //         }
    //     }
    //     else{
    //         return $article_editing;
    //     }
    // }

    // public function add_bisnes_images($images, $bisnes_id)
    // {
    //     foreach ($images as $image) {
    //         // if($file_new_name = ImageControllService::upload_loop_image('images/suport_local_bisnes_img/', $image, 1)){
    //             $file_new_name;
    //             $file_new_name = ImageControllService::upload_loop_image('images/suport_local_bisnes_img/', $image, 1);
    //             if(file_exists(public_path('images/suport_local_bisnes_img/') . $file_new_name)){
    //                 $new_option_image = new Suport_local_bisnes_image;
            
    //                 $new_option_image['image'] = $file_new_name;
    //                 $new_option_image['bisnes_id'] = $bisnes_id;
            
    //                 $saiving = $new_option_image -> save();

    //                 if($saiving){
    //                     echo 'Upload socsesful \n';
    //                 }
    //             }
    //             else{
    //                 echo 'Upload error \n';
    //             }
    //         // }
    //     }
    // }

    // public function add_bisnes_relation($relations, $bisnes_id){
    //     foreach ($relations as $relation) {
    //         if($relation != null || $relation != ''){
    //             $relatione = new Suport_local_bisnes_article;
                
    //             $relatione['article_id'] = $relation;
    //             $relatione['bisnes_id'] = $bisnes_id;
        
    //             $saiving = $relatione -> save();
    //         }
    //     }
    // }
    
    // public function get_editing_local_bisnes_info(Request $request)
    // {
    //     $bisnes = Suport_local_bisnes::where('id', '=', $request->bisnes_id)->first();

    //     $data = [
    //         'global_bisnes' => $bisnes,
    //         'us_bisnes' => $bisnes->us_bisnes,
    //         'ka_bisnes' => $bisnes->ka_bisnes,
    //         // 'ru_bisnes' => $bisnes->ru_bisnes,

    //         // 'bisnes_images' => $bisnes->bisnes_images,
    //     ];

    //     return $data;
    // }
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
    // public function del_local_bisnes(Request $request)
    // {
    //     $bisnes = Suport_local_bisnes::where('id', '=', $request->bisnes_id)->first();
    //     $bisnes_images_count = Suport_local_bisnes_image::where('bisnes_id', '=', $bisnes->id)->count();

    //     if($bisnes_images_count > 0){
    //         $bisnes_images = Suport_local_bisnes_image::where('bisnes_id', '=', $bisnes->id)->get();
    //         // dd($bisnes_images);
    //         foreach ($bisnes_images as $image) {
    //             ImageControllService::image_delete('images/suport_local_bisnes_img/', $image, 'image');
    //             $image ->delete();
    //         }
    //     }
    //     $bisnes ->delete();
    // }

    // public function del_local_bisnes_image(Request $request)
    // {
    //     $image = Suport_local_bisnes_image::where('id', '=', $request->image_id)->first();
    //     // if($image){
    //         ImageControllService::image_delete('images/suport_local_bisnes_img/', $image, 'image');
    //         $image ->delete();
    //     // }
    // }

    // public function del_bisnes_article_relation(Request $request) {
    //     $relation_value = Suport_local_bisnes_article::where('article_id', '=', $request->article_id)->where('bisnes_id', '=', $request->bisnes_id)->first();
    //     $relation_value -> delete();
    // }

}

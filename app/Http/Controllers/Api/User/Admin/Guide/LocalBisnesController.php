<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

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
use App\Services\PermissionService;

use Validator;

class LocalBisnesController extends Controller
{
    const MAX_RELATIONS_PER_ARTICLE = 2;

    public function add_local_bisnes(Request $request)
    {
        $auth = PermissionService::authorize('local_bisnes', 'add');
        if ($auth) return $auth;
        
        try {
            \Log::info('Starting add_local_bisnes request', [
                'has_data' => $request->has('data'),
                'has_files' => $request->hasFile('bisnes_images'),
                'selected_category' => $request->selected_category,
                'has_article_relations' => $request->has('bisnes_new_article_relations')
            ]);

            $data = json_decode($request->data, true );
            
            if (!$data) {
                \Log::error('Failed to decode JSON data', ['raw_data' => $request->data]);
                return response()->json(['error' => 'Invalid JSON data'], 400);
            }
            
            $image_path = 'images/local_bisnes_img/';

            \Log::info('Calling LocaleContentControllService::add_content', [
                'data_keys' => array_keys($data),
                'global_model' => Suport_local_bisnes::class,
                'local_model' => Locale_bisnes::class
            ]);
            
            $article_adding = LocaleContentControllService::add_content($data, Suport_local_bisnes::class, Locale_bisnes::class, '_bisnes', $request, $image_path);

            \Log::info('LocaleContentControllService response', [
                'has_original' => isset($article_adding->original),
                'has_validation' => isset($article_adding->original['validation'])
            ]);

            if (!array_key_exists('validation', $article_adding->original)) {
                // GalleryService::add_gallery_images(
                //     $request->gallery_images, 
                //     $article_adding->original['global_bisnes_id'], 
                //     Article_image::class, 
                //     'image', 
                //     'article_id', 
                //     '/images/local_bisnes_img/'
                // );

                // Get the business object
                $bisnes = Suport_local_bisnes::find($article_adding->original['global_bisnes_id']);
                
                // Save for_article_category to database
                if ($request->selected_category) {
                    $bisnes->for_article_category = $request->selected_category;
                    $bisnes->save();
                } else {
                    // Clear for_article_category if no category selected
                    $bisnes->for_article_category = null;
                    $bisnes->save();
                }
                
                if($request->hasFile('bisnes_images')){
                    $this->add_bisnes_images($request['bisnes_images'], $article_adding->original['global_bisnes_id']);
                }

                // Intelligent relation management for new businesses
                if($request->has('selected_category') || $request->has('bisnes_new_article_relations')) {
                    
                    // Clear existing relations (safety check for new businesses)
                    $bisnes->articles()->detach();
                    
                    // Handle category-based relations
                    if($request->selected_category){
                        $this->add_bisnes_relation_by_category($request->selected_category, $article_adding->original['global_bisnes_id'], $request->excluded_article_ids ?? []);
                    }
                    
                    // Handle manual article relations
                    if($request->bisnes_new_article_relations){
                        $this->add_bisnes_relation($request['bisnes_new_article_relations'], $article_adding->original['global_bisnes_id']);
                    }
                }

                // Return success response with for_article_category data
                return response()->json([
                    'success' => true,
                    'message' => 'Local business added successfully',
                    'data' => [
                        'for_article_category' => $request->selected_category,
                        'global_bisnes_id' => $article_adding->original['global_bisnes_id']
                    ]
                ]);
            } else {
                \Log::warning('Validation errors in add_local_bisnes', ['validation' => $article_adding->original['validation']]);
                
                // Add detailed error logging
                \Log::error('Content validation failed in add', [
                    'validation_details' => $article_adding->original['validation'],
                    'request_data_keys' => array_keys($data),
                    'global_bisnes_keys' => isset($data['global_bisnes']) ? array_keys($data['global_bisnes']) : 'missing',
                    'us_bisnes_keys' => isset($data['us_bisnes']) ? array_keys($data['us_bisnes']) : 'missing',
                    'ka_bisnes_keys' => isset($data['ka_bisnes']) ? array_keys($data['ka_bisnes']) : 'missing'
                ]);
                
                // Return detailed validation errors to help debug
                return response()->json([
                    'validation_needed' => true,
                    'content_validation_errors' => $article_adding->original['validation'],
                    'message' => 'Content validation failed. Please check your input data.',
                    'debug_info' => [
                        'data_structure' => array_keys($data),
                        'missing_fields' => $this->check_required_fields($data)
                    ]
                ], 422);
            }
        } catch (\Exception $e) {
            \Log::error('Exception in add_local_bisnes', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }



    public function edit_local_bisnes(Request $request)
    {
        $auth = PermissionService::authorize('local_bisnes', 'edit');
        if ($auth) return $auth;
        
        try {
            \Log::info('Starting edit_local_bisnes request', [
                'has_data' => $request->has('data'),
                'has_files' => $request->hasFile('bisnes_new_images'),
                'selected_category' => $request->selected_category,
                'has_article_relations' => $request->has('bisnes_new_article_relations')
            ]);

            $image_path = 'images/local_bisnes_img/';

            \Log::info('Calling LocaleContentControllService::edit_content', [
                'global_model' => Suport_local_bisnes::class,
                'local_model' => Locale_bisnes::class
            ]);

            $article_editing = LocaleContentControllService::edit_content(Suport_local_bisnes::class, Locale_bisnes::class, '_bisnes', $request, $image_path);

            \Log::info('LocaleContentControllService edit response', [
                'has_original' => isset($article_editing->original),
                'has_validation' => isset($article_editing->original['validation'])
            ]);

            if(!array_key_exists('validation', $article_editing->original)) {
                // Get the business object
                $bisnes = Suport_local_bisnes::find($article_editing->original['global_bisnes_id']);
                
                // Save for_article_category to database
                if ($request->selected_category) {
                    $bisnes->for_article_category = $request->selected_category;
                    $bisnes->save();
                } else {
                    // Clear for_article_category if no category selected
                    $bisnes->for_article_category = null;
                    $bisnes->save();
                }
                
                // Handle images if any
                if($request->hasFile('bisnes_new_images')){
                    $this->add_bisnes_images($request['bisnes_new_images'], $article_editing->original['global_bisnes_id']);
                }
                
                // Intelligent relation management - only update if relation data is provided
                if($request->has('selected_category') || $request->has('bisnes_new_article_relations')) {
                    
                    // Clear existing relations only when updating relations
                    $bisnes->articles()->detach();
                    
                    // Handle category-based relations
                    if($request->selected_category){
                        $this->add_bisnes_relation_by_category($request->selected_category, $article_editing->original['global_bisnes_id'], $request->excluded_article_ids ?? []);
                    }
                    
                    // Handle manual article relations
                    if($request->bisnes_new_article_relations){
                        $this->add_bisnes_relation($request['bisnes_new_article_relations'], $article_editing->original['global_bisnes_id']);
                    }
                }

                // Return success response with for_article_category data
                return response()->json([
                    'success' => true,
                    'message' => 'Local business updated successfully',
                    'data' => [
                        'for_article_category' => $request->selected_category,
                        'global_bisnes_id' => $article_editing->original['global_bisnes_id']
                    ]
                ]);
            } else {
                \Log::warning('Validation errors in edit_local_bisnes', ['validation' => $article_editing->original['validation']]);
                
                // Add detailed error logging
                \Log::error('Content validation failed', [
                    'validation_details' => $article_editing->original['validation'],
                    'request_data_keys' => array_keys(json_decode($request->data, true) ?: [])
                ]);
                
                // Return detailed validation errors to help debug
                return response()->json([
                    'validation_needed' => true,
                    'content_validation_errors' => $article_editing->original['validation'],
                    'message' => 'Content validation failed. Please check your input data.',
                    'debug_info' => [
                        'data_structure' => json_decode($request->data, true) ? array_keys(json_decode($request->data, true)) : 'Invalid JSON'
                    ]
                ], 422);
            }
        } catch (\Exception $e) {
            \Log::error('Exception in edit_local_bisnes', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function add_bisnes_images($images, $bisnes_id)
    {
        foreach ($images as $image) {
            // if($file_new_name = ImageControllService::upload_loop_image('images/suport_local_bisnes_img/', $image, 1)){
                $file_new_name;
                $file_new_name = ImageControllService::upload_loop_image('images/suport_local_bisnes_img/', $image, 1);
                if(file_exists(public_path('images/suport_local_bisnes_img/') . $file_new_name)){
                    $new_option_image = new Suport_local_bisnes_image;
            
                    $new_option_image['image'] = $file_new_name;
                    $new_option_image['bisnes_id'] = $bisnes_id;
            
                    $saiving = $new_option_image -> save();

                    if($saiving){
                        echo 'Upload socsesful \n';
                    }
                }
                else{
                    echo 'Upload error \n';
                }
            // }
        }
    }


    public function add_bisnes_relation($relations, $bisnes_id){
        foreach ($relations as $relation) {
            if($relation != null || $relation != ''){
                // First, delete existing relation between this article and this business, if it exists
                Suport_local_bisnes_article::where('article_id', $relation)
                                          ->where('bisnes_id', $bisnes_id)
                                          ->delete();

                $this->evict_oldest_relation_if_at_capacity($relation, $bisnes_id);

                // Then create new relation
                $relatione = new Suport_local_bisnes_article;

                $relatione['article_id'] = $relation;
                $relatione['bisnes_id'] = $bisnes_id;

                $saiving = $relatione -> save();
            }
        }
    }

    /**
     * An article may be related to at most self::MAX_RELATIONS_PER_ARTICLE businesses.
     * If the article is already at (or over) the cap, delete its oldest relation
     * (excluding any relation to $bisnes_id itself) to make room for the new one.
     */
    private function evict_oldest_relation_if_at_capacity($article_id, $bisnes_id)
    {
        $existing_relations = Suport_local_bisnes_article::where('article_id', $article_id)
            ->where('bisnes_id', '!=', $bisnes_id)
            ->orderBy('id')
            ->get();

        $overflow = $existing_relations->count() - self::MAX_RELATIONS_PER_ARTICLE + 1;

        if ($overflow > 0) {
            $existing_relations->take($overflow)->each(function ($relation) {
                $relation->delete();
            });
        }
    }



    public function get_editing_local_bisnes_info($bisnes_id)
    {
        if ($auth = PermissionService::authorize('local_bisnes', 'show')) return $auth;
        if (!$bisnes_id) {
            return response()->json(['error' => 'Business ID is required'], 400);
        }

        $bisnes = Suport_local_bisnes::where('id', '=', $bisnes_id)->first();

        if (!$bisnes) {
            return response()->json(['error' => 'Business not found'], 404);
        }


        $data = [
            'global_bisnes' => $bisnes,
            'us_bisnes' => $bisnes->us_bisnes,
            'ka_bisnes' => $bisnes->ka_bisnes,
            // 'ru_bisnes' => $bisnes->ru_bisnes,
            'bisnes_images' => $bisnes->bisnes_images,
            'for_article_category' => $bisnes->for_article_category,
        ];

        return $data;
    }
    

    public function get_bisnes_article_relation(Request $request)
    {
        if ($auth = PermissionService::authorize('local_bisnes', 'show')) return $auth;
        $bisnes = Suport_local_bisnes::where('id', '=', $request->bisnes_id)->first();

        // Return article relations with article details
        $relations = [];
        foreach ($bisnes->articles as $article) {
            $relations[] = [
                'article_id' => $article->id,
                'article_url_title' => $article->url_title,
                'article_category' => $article->category
            ];
        }

        return $relations;
    }


    public function get_bisnes_article_relations($bisnes_id)
    {
        if ($auth = PermissionService::authorize('local_bisnes', 'show')) return $auth;
        $bisnes = Suport_local_bisnes::where('id', '=', $bisnes_id)->first();

        if (!$bisnes) {
            return response()->json(['error' => 'Business not found'], 404);
        }

        // Whether this business is in category mode or individual mode is decided
        // authoritatively by for_article_category - this endpoint just lists the
        // business's currently related articles (used to populate individual mode).
        return $bisnes->articles->map(function ($article) {
            return [
                'article_id' => $article->id,
                'article_url_title' => $article->url_title,
                'article_category' => $article->category,
            ];
        })->values();
    }

    public function del_local_bisnes(Request $request)
    {
        $auth = PermissionService::authorize('local_bisnes', 'del');
        if ($auth) return $auth;
        
        $bisnes = Suport_local_bisnes::where('id', '=', $request->bisnes_id)->first();
        $bisnes_images_count = Suport_local_bisnes_image::where('bisnes_id', '=', $bisnes->id)->count();

        if($bisnes_images_count > 0){
            $bisnes_images = Suport_local_bisnes_image::where('bisnes_id', '=', $bisnes->id)->get();
            // dd($bisnes_images);
            foreach ($bisnes_images as $image) {
                ImageControllService::image_delete('images/suport_local_bisnes_img/', $image, 'image');
                $image ->delete();
            }
        }
        $bisnes ->delete();
    }

    public function del_local_bisnes_image(Request $request)
    {
        $image = Suport_local_bisnes_image::where('id', '=', $request->image_id)->first();
        // if($image){
            ImageControllService::image_delete('images/suport_local_bisnes_img/', $image, 'image');
            $image ->delete();
        // }
    }



    public function del_bisnes_article_relation(Request $request) {
        $auth = PermissionService::authorize('local_bisnes', 'edit');
        if ($auth) return $auth;

        $relation_value = Suport_local_bisnes_article::where('article_id', '=', $request->article_id)->where('bisnes_id', '=', $request->bisnes_id)->first();
        if ($relation_value) {
            $relation_value->delete();
        }
    }


    public function get_article_categories(Request $request)
    {
        if ($auth = PermissionService::authorize('local_bisnes', 'show')) return $auth;
        // Get unique categories from articles table with their IDs
        $categories = Article::whereNotNull('category')
                           ->where('category', '!=', '')
                           ->distinct()
                           ->select('category')
                           ->orderBy('category')
                           ->get()
                           ->map(function($item) {
                               return [
                                   'id' => $item->category, // Use category name as ID
                                   'name' => $item->category
                               ];
                           });
        
        return $categories;
    }

    /**
     * Read-only overview used by the relation-editing UI: for a category (categoriable
     * mode) or an explicit list of article ids (individual mode), return every matching
     * article together with ALL of its current business relations - not just conflicts.
     */
    public function get_article_relations_overview(Request $request)
    {
        if ($auth = PermissionService::authorize('local_bisnes', 'show')) return $auth;

        if ($request->category) {
            $articles = Article::where('category', $request->category)->get();
        } elseif ($request->has('article_ids')) {
            $article_ids = array_filter($request->article_ids);
            $articles = $article_ids ? Article::whereIn('id', $article_ids)->get() : collect();
        } else {
            $articles = collect();
        }

        $result = $articles->map(function ($article) {
            $related_businesses = Suport_local_bisnes_article::where('article_id', $article->id)
                ->orderBy('id')
                ->get()
                ->map(function ($relation) {
                    $business = Suport_local_bisnes::find($relation->bisnes_id);
                    return [
                        'bisnes_id' => $relation->bisnes_id,
                        'bisnes_title' => $business ? $business->url_title : null,
                    ];
                })
                ->values();

            return [
                'article_id' => $article->id,
                'article_title' => $article->url_title,
                'article_category' => $article->category,
                'related_businesses' => $related_businesses,
            ];
        })->values();

        return response()->json(['articles' => $result]);
    }

    public function add_bisnes_relation_by_category($category, $bisnes_id, $excluded_article_ids = [])
    {
        // Find all articles with the specified category, minus any the admin chose to skip
        $articles = Article::where('category', $category)
            ->whereNotIn('id', $excluded_article_ids)
            ->get();

        $created_relations = [];
        foreach ($articles as $article) {
            // Check if relation already exists
            $existing_relation = Suport_local_bisnes_article::where('article_id', $article->id)
                                                            ->where('bisnes_id', $bisnes_id)
                                                            ->first();

            // If relation doesn't exist, create it
            if (!$existing_relation) {
                $this->evict_oldest_relation_if_at_capacity($article->id, $bisnes_id);

                $relation = new Suport_local_bisnes_article();
                $relation->article_id = $article->id;
                $relation->bisnes_id = $bisnes_id;
                $relation->save();

                $created_relations[] = $relation;
            }
        }

        return $created_relations;
    }

    /**
     * Helper method to check required fields and provide debugging info
     */
    private function check_required_fields($data)
    {
        $missing = [];
        
        // Check global_bisnes fields
        if (!isset($data['global_bisnes'])) {
            $missing[] = 'global_bisnes section missing';
        } else {
            if (!isset($data['global_bisnes']['published'])) {
                $missing[] = 'global_bisnes.published is required';
            }
        }
        
        // Check us_bisnes fields  
        if (!isset($data['us_bisnes'])) {
            $missing[] = 'us_bisnes section missing';
        } else {
            if (empty($data['us_bisnes']['title'])) {
                $missing[] = 'us_bisnes.title is required';
            }
            if (empty($data['us_bisnes']['short_description'])) {
                $missing[] = 'us_bisnes.short_description is required';
            }
            if (empty($data['us_bisnes']['text'])) {
                $missing[] = 'us_bisnes.text is required';
            }
        }
        
        // Check ka_bisnes fields
        if (!isset($data['ka_bisnes'])) {
            $missing[] = 'ka_bisnes section missing';
        } else {
            if (empty($data['ka_bisnes']['title'])) {
                $missing[] = 'ka_bisnes.title is required';
            }
            if (empty($data['ka_bisnes']['short_description'])) {
                $missing[] = 'ka_bisnes.short_description is required';
            }
            if (empty($data['ka_bisnes']['text'])) {
                $missing[] = 'ka_bisnes.text is required';
            }
        }
        
        return $missing;
    }

    /**
     * Dry-run check: does proceeding with these proposed relations push any article
     * over the MAX_RELATIONS_PER_ARTICLE cap? Read-only - no relations are created,
     * moved, or deleted here. The frontend calls this before saving to warn the admin
     * and let them decide (rewrite/skip) per article; the actual eviction happens in
     * add_bisnes_relation() / add_bisnes_relation_by_category() at save time.
     */
    public function check_article_relation_capacity(Request $request)
    {
        if ($auth = PermissionService::authorize('local_bisnes', 'show')) return $auth;

        $editing_business_id = $request->editing_business_id ?: null;

        $article_ids = [];

        if ($request->has('bisnes_new_article_relations')) {
            foreach ($request->bisnes_new_article_relations as $article_id) {
                if ($article_id) {
                    $article_ids[] = $article_id;
                }
            }
        }

        if ($request->has('selected_category') && $request->selected_category) {
            $article_ids = array_merge(
                $article_ids,
                Article::where('category', $request->selected_category)->pluck('id')->all()
            );
        }

        $conflicts = [];

        foreach (array_unique($article_ids) as $article_id) {
            $query = Suport_local_bisnes_article::where('article_id', $article_id);
            if ($editing_business_id) {
                $query->where('bisnes_id', '!=', $editing_business_id);
            }
            $existing_relations = $query->orderBy('id')->get();

            if ($existing_relations->count() < self::MAX_RELATIONS_PER_ARTICLE) {
                continue;
            }

            $article = Article::find($article_id);
            if (!$article) {
                continue;
            }

            $existing = $existing_relations->map(function ($relation) {
                $business = Suport_local_bisnes::find($relation->bisnes_id);
                return [
                    'bisnes_id' => $relation->bisnes_id,
                    'bisnes_title' => $business ? $business->url_title : null,
                ];
            })->values();

            $conflicts[] = [
                'article_id' => $article->id,
                'article_title' => $article->url_title,
                'article_category' => $article->category,
                'existing_relations' => $existing,
                'oldest_relation' => $existing->first(),
            ];
        }

        return response()->json([
            'max_relations' => self::MAX_RELATIONS_PER_ARTICLE,
            'conflicts' => $conflicts,
        ]);
    }

}

<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guide\Article;
use App\Models\Guide\Sector;
use App\Models\Guide\Route;
use App\Models\Guide\Mtp;
use App\Models\Guide\Mtp_pitch;
use App\Models\Guide\Sector_image;
use App\Models\Guide\Spot_rocks_image;
use App\Models\Guide\Sector_local_image;
use App\Models\Guide\Sector_local_image_sector;

use App\Services\Abstract\ImageControllService;

use Validator;

class SectorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_all_sectors()
    {
        return Sector::latest('id')->get();
    }

    public function get_sectors_by_article_category(Request $request)
    {
        $categoryed_articles = Article::where('category', '=', $request->article_category)->get();
        
        $sectors = [];
        foreach ($categoryed_articles as $categoryed_article) {
            foreach ($categoryed_article->sectors as $sector) {
                array_push($sectors, $sector);
            }
        }

        usort($sectors, function($a, $b) {
            return $b->id <=> $a->id;
        });

        return $sectors;
    }

    public function get_spot_sectors_data_for_model(Request $request)
    {
        return Sector::where('article_id','=', $request->article_id)->orderBy('num')->get();
    }

    public function add_sector(Request $request)
    {
        $data = json_decode($request->data, true );
        $validate = $this->sector_validate($data);

        if ($validate != null) {
            return response()->json($validate, 422);
        }
        else{
            $spot_sector_count = Sector::where('article_id', '=', $data['article_id'])->count();
            $new_sector = new Sector();

            $new_sector['num'] = $spot_sector_count++;
            $new_sector['published'] = $data['published'];
            $new_sector['article_id'] = $data['article_id'];
            $new_sector['name'] = $data['name'];
            $new_sector['text'] = $data['text'];
            $new_sector['all_day_in_shade'] = $data['all_day_in_shade'];
            $new_sector['all_day_in_sun'] = $data['all_day_in_sun'];
            $new_sector['in_the_shade_afternoon'] = $data['in_the_shade_afternoon'];
            $new_sector['in_the_shade_befornoon'] = $data['in_the_shade_befornoon'];
            $new_sector['in_shade_after_10'] = $data['in_shade_after_10'];
            $new_sector['in_shade_after_15'] = $data['in_shade_after_15'];
            $new_sector['slabby'] = $data['slabby'];
            $new_sector['vertical'] = $data['vertical'];
            $new_sector['overhang'] = $data['overhang'];
            $new_sector['roof'] = $data['roof'];

            $new_sector['for_family'] = $data['for_family'];
            $new_sector['for_kids'] = $data['for_kids'];
            $new_sector['wolking_time'] = $data['wolking_time'];
            $new_sector['is_helmet'] = $data['is_helmet'];

            $save_sector = $new_sector -> save();

            if($request->sector_images){
                if(!$save_sector){
                    App::abort(500, 'Saving error');
                }
                else{
                    $this->add_sector_images($request->sector_images, $new_sector->id);
                }
            }
        }
    }

    public function get_spot_rocks_images(Request $request)
    {
        return (Spot_rocks_image::where('article_id','=', $request->article_id)->get());
    }

    public function get_sector_and_routes(Request $request, $article_id)
    {
        $sectors = Sector::where('article_id','=', $article_id)->where('published', '=', 1)->orderBy('num')->get();
        $area_info = array();

        if(count($sectors)){

            $area_local_images = $this->get_area_local_images($sectors);

            if(count($area_local_images)){
                foreach ($area_local_images as $area_local_image) {
                    array_push($area_info, 
                        array(
                            "local_images" => [$area_local_image],
                            "sectors" => []
                        )
                    );
                    foreach ($area_local_image->sectors as $area_local_image_sectors) {
                        array_push($area_info[count($area_info)-1]["sectors"], 
                            $this->get_sector_data($area_local_image_sectors->id)
                        );
                    }
                }
                foreach($sectors as $sector){
                    $found = false;
                    foreach ($area_info as $area) {
                        if(isset($area["sectors"])){
                            foreach ($area["sectors"] as $area_sector) {
                                if ($sector->id == $area_sector['sector']['id']) {
                                    $found = true;
                                    break;
                                }
                            }
                        }
                    }
                    if (!$found) {
                        array_push($area_info, $this->get_sector_data($sector->id) );
                    }
                }
            }
            else{
                foreach($sectors as $sector){
                    array_push($area_info, 
                        $this->get_sector_data($sector->id)
                    );
                }
            }
        }

        return $area_info;
    }

    public function get_area_local_images($sector_model)
    {
        $area_local_images_relationes = array();
        $images = array();

        if(count($sector_model)){
            foreach($sector_model as $sector){
                $area_local_image = Sector_local_image_sector::where('sector_id', '=', $sector->id)->first();
                if($area_local_image){
                    array_push($area_local_images_relationes, 
                        $area_local_image
                    );
                }
            }
        }

        foreach ($area_local_images_relationes as $area_local_images_relatione) {
            if (count($images) == 0) {
                array_push($images, Sector_local_image::where('id', '=', $area_local_images_relatione->image_id)->first());
            }
            else if (count($images) != 0 && !in_array($area_local_images_relatione->image_id, array_column($images, 'id'))) {
                array_push($images, Sector_local_image::where('id', '=', $area_local_images_relatione->image_id)->first());
            }
        }

        return $images;
    }

    public function get_sector_data($sector_id)
    {
        $area_info = array();
        $sector_imgs = array();
        $sport_route_info = array();
        $boulder_route_info = array();
        $mtp_info = array();
        $mtp_pitch_info = array();

        $sector = Sector::where('id', '=', $sector_id)->orderBy('num')->first();
                
        $sector_imgs = $sector->images->take(6);
        if ($sector_imgs) {
            $sector_imgs = $sector_imgs;
        }
        else $sector_imgs = array();

        $sport_routes = $sector->sport_routes;
        if ($sport_routes){
            $sport_route_info = $sport_routes;
        }
        else $sport_route_info = array();

        $boulder_routes = $sector->boulder_routes;
        if ($boulder_routes){
            $boulder_route_info = $boulder_routes;
        }
        else $boulder_route_info = array();

        $mtps = $sector->mtps;
        if ($mtps){
            $mtp_info = array();
            foreach($mtps as $mtp){
                $mtp_pitchs = Mtp_pitch::where('mtp_id', '=', $mtp->id)->orderBy('num')->get();
                $mtp_pitchs_count = Mtp_pitch::where('mtp_id', '=', $mtp->id)->orderBy('num')->count();
                if ($mtp_pitchs_count > 0) {
                    $mtp_pitch_info = array();
                    foreach($mtp_pitchs as $mtp_pitch){
                        array_push($mtp_pitch_info,
                            $mtp_pitch
                        );
                    }
                }
                array_push($mtp_info, 
                    [
                        "mtp_id"=>$mtp['id'],
                        "mtp_num"=>$mtp['num'],
                        "mtp_name"=>$mtp['name'],
                        "mtp_text"=>$mtp['text'],
                        "mtp_pitchs"=>$mtp_pitch_info
                    ]
                );
            }
        }
        else $mtp_info = array();
        
        array_push($area_info, 
            array(
                "sector" => $sector,  
                'sector_imgs'=>$sector_imgs,
                "sport_routes" => $sport_route_info,
                "boulder_route"=>$boulder_route_info,
                "mtps" => $mtp_info,
            )
        );
        
        return $area_info[0];
    }

    public function edit_sector(Request $request, )
    {
        $data = json_decode($request->data, true );
        $validate = $this->sector_validate($data);

        if ($validate != null) {
            return response()->json($validate, 422);
        }
        else{
            $edit_sector = Sector::where("id", "=", $request->sector_id)->first();

            $edit_sector['published'] = $data['published'];
            $edit_sector['article_id'] = $data['article_id'];
            $edit_sector['name'] = $data['name'];
            $edit_sector['text'] = $data['text'];
            $edit_sector['all_day_in_shade'] = $data['all_day_in_shade'];
            $edit_sector['all_day_in_sun'] = $data['all_day_in_sun'];
            $edit_sector['in_the_shade_afternoon'] = $data['in_the_shade_afternoon'];
            $edit_sector['in_the_shade_befornoon'] = $data['in_the_shade_befornoon'];
            $edit_sector['in_shade_after_10'] = $data['in_shade_after_10'];
            $edit_sector['in_shade_after_15'] = $data['in_shade_after_15'];
            $edit_sector['slabby'] = $data['slabby'];
            $edit_sector['vertical'] = $data['vertical'];
            $edit_sector['overhang'] = $data['overhang'];
            $edit_sector['roof'] = $data['roof'];

            $edit_sector['for_family'] = $data['for_family'];
            $edit_sector['for_kids'] = $data['for_kids'];
            $edit_sector['wolking_time'] = $data['wolking_time'];
            $edit_sector['is_helmet'] = $data['is_helmet'];

            $save_sector = $edit_sector -> save();
            
            if($request->sector_new_images){
                if(!$save_sector){
                    App::abort(500, 'Saving error');
                }
                else{
                    $this->add_sector_images($request->sector_new_images, $edit_sector->id);
                }
            }
        }
    }

    public function get_region_sectors(Request $request)
    {
        return Sector::where('article_id', '=', $request->region_id)->get();
    }

    public function routes_sequence(Request $request)
    {
        // dd($request);
        if($request->routes_sequence){
            $route_num = 0;
            foreach ($request->routes_sequence as $route) {
                $route_id = $route['id'];
                $route = Route::where('id',strip_tags($route_id))->first();
                $route_num++;
                $route['num'] = $route_num;
                $route->update();
            }
        }

        if($request->mtp_sequence){
            $mtp_num=0;
            foreach ($request->mtp_sequence as $mtp) {
                $mtp_id = $mtp['id'];
                $mtp = mtp::where('id',strip_tags($mtp_id))->first();
                $mtp_num++;
                $mtp['num'] = $mtp_num;
                $mtp->update();
            }
        }

        if($request->sector_images_sequence){
            $image_num=0;
            foreach ($request->sector_images_sequence as $image) {
                $image_id = $image['id'];
                // dd($image_id);
                $image = Sector_image::where('id',strip_tags($image_id))->first();
                $image_num++;
                $image['num'] = $image_num;
                $image->update();
            }
        }
    }

    public function get_sectors_and_routes_quantity()
    {
        $data = [
            'sectors' => Sector::count(),
            'mtps' => MTP::count(),
            'sport_routes' => Route::where("category","=","sport climbing")->orWhere("category","=","top")->count(),
            'boulder_routes' => Route::where("category","=","bouldering")->count(),
        ];

        return $data;
    }

    public function sectors_and_routes_quantity_by_categories(Request $request)
    {
        // Map frontend category values to database categories
        $categoryMap = [
            'sport' => [
                'db_category' => ['sport climbing', 'top'],
                'label' => 'sport',
                'route_label' => 'sport_routes'
            ],
            'boulder' => [
                'db_category' => ['bouldering'],
                'label' => 'boulder',
                'route_label' => 'boulder_routes'
            ],
            'ice' => [
                'db_category' => ['ice climbing'],
                'label' => 'ice',
                'route_label' => 'ice_routes'
            ],
            'dry' => [
                'db_category' => ['dry tooling'],
                'label' => 'dry',
                'route_label' => 'dry_routes'
            ],
            'mtp' => [
                'db_category' => null,
                'label' => 'mtp',
                'route_label' => 'mtps'
            ],
        ];
        
        // Get route_categories from request body (POST) or query parameters
        $routeCategories = $request->input('route_categories');
        
        // If not in body, check query parameters
        if ($routeCategories === null) {
            $routeCategories = $request->query('route_categories');
        }
        
        // If still null, check for 'categories' parameter
        if ($routeCategories === null) {
            $routeCategories = $request->query('categories');
        }
        
        // If still null, return all data (fallback to original method)
        if ($routeCategories === null) {
            return $this->get_sectors_and_routes_quantity();
        }
        
        // Ensure it's an array
        if (!is_array($routeCategories)) {
            $routeCategories = [$routeCategories];
        }
        
        $categoryData = [];
        
        foreach ($routeCategories as $category) {
            if (!isset($categoryMap[$category])) {
                continue;
            }
            
            $config = $categoryMap[$category];
            
            // Count routes for this category
            $routeCount = 0;
            if ($config['db_category'] !== null) {
                $query = Route::query();
                foreach ($config['db_category'] as $dbCat) {
                    $query->orWhere("category", "=", $dbCat);
                }
                $routeCount = $query->count();
            }
            
            // For mtp, count mtps
            if ($config['label'] === 'mtp') {
                $routeCount = MTP::count();
            }
            
            // Count sectors that have routes of this category
            $sectorCount = 0;
            if ($config['db_category'] !== null) {
                $sectorIds = Route::where(function($q) use ($config) {
                    foreach ($config['db_category'] as $dbCat) {
                        $q->orWhere("category", "=", $dbCat);
                    }
                })->distinct('sector_id')->pluck('sector_id');
                $sectorCount = count($sectorIds);
            }
            
            // For mtp, count sectors that have mtps
            if ($config['label'] === 'mtp') {
                $sectorIds = MTP::distinct('sector_id')->pluck('sector_id');
                $sectorCount = count($sectorIds);
            }
            
            $categoryData[$category] = [
                'sectors' => $sectorCount,
                $config['route_label'] => $routeCount
            ];
        }
        
        return [
            'categories' => $categoryData
        ];
    }

    public function get_routes_for_model(Request $request)
    {
        $routes = Route::where('sector_id',strip_tags($request->sector_id))->orderBy('num')->get();
        return( $routes );
    }

	public function get_mtp_for_model(Request $request)
    {
        $mtps = Mtp::where('sector_id',strip_tags($request->sector_id))->orderBy('num')->get();
        return( $mtps );
    }

	public function get_mtp_pitchs_for_model(Request $request)
    {
        $mtp_pitchs = Mtp_pitch::where('mtp_id',strip_tags($request->mtp_id))->orderBy('num')->get();
        return( $mtp_pitchs );
    }

	public function get_sector_data_for_model(Request $request)
    {
        $sector = Sector::where('id', '=', $request->sector_id)->first();
                
        $images = $sector->images->take(6);
        if ($images) {
            $images = $images;
        }
        else $images = array();

        $routes = $sector->routes;
        if ($routes){
            $sport_route_info = $routes;
        }
        else $sport_route_info = array();

        $mtps = $sector->mtps;
        if ($mtps){
            $mtps = $mtps;
        }

        $data = [
            'images' => $images,
            'routes' => $routes,
            'mtps' => $mtps,
        ];
        
        return( $data );
    }

    public function get_sector_images(Request $request)
    {
        $sector = Sector::where('id',strip_tags($request->sector_id))->first();
        return $sector->images;
    }
}

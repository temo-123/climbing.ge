<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Sector;
use App\Models\Route;
use App\Models\Mtp;
use App\Models\Mtp_pitch;
use App\Models\Sector_image;
use App\Models\Spot_rocks_image;
// use App\Models\Comment;
// use App\Models\Gallery;

use App\Services\ImageControllService;

use Validator;

class SectorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Sector::get();
    }

    public function get_sectors_for_forum($article_id)
    {
        return Sector::where('article_id','=', $article_id)->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_sector(Request $request)
    {
        $data = json_decode($request->data, true );
        $validate = $this->sector_validate($data);

        if ($validate != null) {
            return response()->json($validate, 422);
        }
        else{
            $new_sector = new Sector();

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

            $save_sector = $new_sector -> save();

            if($request->sector_images){
                if(!$save_sector){
                    App::abort(500, 'Saiving error');
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

    public function add_sector_images($images, $sector_id)
    {
        foreach ($images as $image) {
            $file_new_name;
            $file_new_name = ImageControllService::upload_loop_image('images/sector_img/', $image, 1);
            if(file_exists(public_path('images/sector_img/') . $file_new_name)){
                $new_option_image = new Sector_image;

                $sector_images_count = Sector_image::where('sector_id',strip_tags($sector_id))->count();
                if($sector_images_count == 0){
                    $new_route_num = 1;
                }
                else{
                    $new_option_image['num'] = $sector_images_count+1;
                }

                $new_option_image['image'] = $file_new_name;
                $new_option_image['sector_id'] = $sector_id;
        
                $saiving = $new_option_image -> save();

                if($saiving){
                    echo 'Upload socsesful \n';
                }
            }
            else{
                echo 'Upload error \n';
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $pitch_num_in_array = 0;
        $mtp_num_in_array = 0;
        $sport_route_num = 0;
        $bolder_route_num = 0;
        $mtp_pitch_num = 0;

        $area_info = array();
        $sector_imgs = array();
        $sport_route_info = array();
        $boulder_route_info = array();
        $mtp_info = array();
        $mtp_pitch_info = array();

        $spot_rocks_images = array();

        $sector_count = Sector::where('article_id', '=', $id)->count();
        if ($sector_count > 0) {
            $sector_n = Sector::where('article_id', '=', $id)->orderBy('num')->get();

            foreach($sector_n as $sector){
                $sectors_img_count = Sector_image::where('sector_id', '=', $sector->id)->count();
                if ($sectors_img_count > 0) {
                    $sectors_img = Sector_image::limit(10)->where('sector_id', '=', $sector->id)->orderBy('num')->get();

                    $sector_imgs = array();
                    foreach($sectors_img as $sector_img){
                        array_push($sector_imgs, 
                            array(
                                'id' => $sector_img->id,
                                'image' => $sector_img->image,
                                )   
                        );
                    }
                }
                else $sector_imgs = array();

                $sport_routes_count = Route::where('sector_id', '=', $sector->id)->where('category', '=', 'sport climbing')->orWhere('category', '=', 'top')->count();
                if ($sport_routes_count > 0){
                    $sport_routes = Route::where('sector_id', '=', $sector->id)->where('category', '=', 'sport climbing')->orWhere('category', '=', 'top')->orderBy('num')->get();
                    $sport_route_info = array();
                    foreach($sport_routes as $x=>$route){
                        $sport_route_num++;
                        array_push($sport_route_info, 
                            array(
                                "id"=>$route['id'], 
                                "num"=>$route['num'],
                                "name"=>$route['name'], 
                                "text"=>$route['text'], 
                                "height"=>$route['height'], 
                                "bolts"=>$route['bolts'], 
                                "grade_fr"=>$route['grade'],
                                "or_grade_fr"=>$route['or_grade'],
                                "last_carabin"=>$route['last_carabin'], 
                                "first_ascent"=>$route['first_ascent'], 
                                "bolter"=>$route['bolter'], 
                                "category"=>$route['category'], 
                                "dolting_data"=>$route['dolting_data'],
                            )
                        );
                    }
                    // dd($sport_route_info);
                    $sport_route_num = 0;
                }
                else $sport_route_info = array();

                $boulder_routes_count = Route::where('sector_id', '=', $sector->id)->where('category', '=', 'bouldering')->count();
                if ($boulder_routes_count > 0){
                    $boulder_routes = Route::where('sector_id', '=', $sector->id)->where('category', '=', 'bouldering')->orderBy('num')->get();
                    $boulder_route_info = array();
                    foreach($boulder_routes as $x=>$route){
                        $bolder_route_num++;
                        array_push($boulder_route_info, 
                            array(
                                "id"=>$route['id'], 
                                "num"=>$route['num'], 
                                "name"=>$route['name'], 
                                "text"=>$route['text'], 
                                "height"=>$route['height'], 
                                "grade_fr"=>$route['grade'],
                                "or_grade_fr"=>$route['or_grade'],
                                "category"=>$route['category'], 
                                "dolting_data"=>$route['dolting_data'],
                            )
                        );
                    }
                    $bolder_route_num = 0;
                }
                else $boulder_route_info = array();

                $mtps_count = MTP::where('sector_id', '=', $sector->id)->count();
                if ($mtps_count > 0){
                    $mtps = MTP::where('sector_id', '=', $sector->id)->orderBy('num')->get();
                    $mtp_info = array();
                    foreach($mtps as $mtp){
                        $mtp_pitchs = Mtp_pitch::where('mtp_id', '=', $mtp->id)->orderBy('num')->get();
                        $mtp_pitchs_count = Mtp_pitch::where('mtp_id', '=', $mtp->id)->orderBy('num')->count();
                        if ($mtp_pitchs_count > 0) {
                            $mtp_pitch_info = array();
                            foreach($mtp_pitchs as $mtp_pitch){
                                array_push($mtp_pitch_info,
                                    [
                                        'pitch_id'=>$mtp_pitch['id'],
                                        'pitch_num'=>$mtp_pitch['num'],
                                        'pitch_name'=>$mtp_pitch['name'],
                                        "pitch_text"=>$mtp_pitch['text'], 
                                        "pitch_height"=>$mtp_pitch['height'], 
                                        "pitch_bolts"=>$mtp_pitch['bolts'], 
                                        "pitch_grade_fr"=>$mtp_pitch['grade'],
                                        "pitch_or_grade_fr"=>$mtp_pitch['or_grade'],
                                        "pitch_first_ascent"=>$mtp_pitch['first_ascent'], 
                                        "pitch_bolter"=>$mtp_pitch['bolter'], 
                                        "pitch_dolting_data"=>$mtp_pitch['dolting_data'],
                                    ]
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

                $sector_info = array(
                    "id"=>$sector->id, 
                    "name"=>$sector->name, 
                    "text"=>$sector->text, 

                    "all_day_in_shade"=>$sector->all_day_in_shade,
                    "all_day_in_sun"=>$sector->all_day_in_sun,
                    "in_the_shade_afternoon"=>$sector->in_the_shade_afternoon,
                    "in_the_shade_befornoon"=>$sector->in_the_shade_befornoon,
                    "in_shade_after_10"=>$sector->in_shade_after_10,
                    "in_shade_after_15"=>$sector->in_shade_after_15,

                    "overhang"=>$sector->overhang,
                    "slabby"=>$sector->slabby,
                    "vertical"=>$sector->vertical,
                    "roof"=>$sector->roof,

                    'sector_img'=>$sector_imgs,
                );

                // dd(Spot_rocks_image_sector::find($sector->id)->images);

                // $spot_rocks_images = Spot_rocks_image_sector::find($sector->id)->images;
                // dd($spot_rocks_images);
                
                array_push($area_info, 
                    array(
                        "sectors" => $sector_info,  
                        "sport_routes" => $sport_route_info,
                        "boulder_route"=>$boulder_route_info,
                        "mtps" => $mtp_info,
                        // "spot_rocks_images"=>$spot_rocks_images,
                    )
                );
            }
        }
        
        return $area_info;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    // public function get_Spot_rocks_images(Request $request)
    // {
    //     return (Spot_rocks_image::where('article_id','=', $request->article_id)->get());
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

            $save_sector = $edit_sector -> save();
            
            if($request->sector_new_images){
                if(!$save_sector){
                    App::abort(500, 'Saiving error');
                }
                else{
                    $this->add_sector_images($request->sector_new_images, $edit_sector->id);
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function del_sector_sector(Request $request)
    {
        $sector_id=$request->sector_id;

        $sector = Sector::where('id',strip_tags($sector_id))->first();

        // delete product file
        $sector_images = Sector_image::where('sector_id',strip_tags($sector_id))->get();
        $sector_images_count = Sector_image::where('sector_id',strip_tags($sector_id))->count();
        // dd($sector_images_count);
        if ($sector_images_count > 0) {
            foreach ($sector_images as $sector_image) {
                imageControllService::image_delete('images/sector_img/', $sector_image, $request);
                $sector_image ->delete();
            }
        }

        // delete product from db
        $sector ->delete();
    }


    public function get_region_sectors(Request $request)
    {
        return Sector::where('article_id', '=', $request->region_id)->get();
    }



    public function sector_image_validate($request)
    {
        $request->validate([
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    }

    private function sector_validate($sector_data)
    {
        $validator = Validator::make($sector_data, [
            'name' => 'required|max:190',
            'article_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }
    }

    // public static function get_yds_grade($route)
    // {
    //     if ($route['grade'] == '4') $grade_yds = '5.6';
    //     elseif ($route['grade'] == '5a'||$route['grade'] == '5a+') $grade_yds = '5.7';
    //     elseif ($route['grade'] == '5b'||$route['grade'] == '5b+') $grade_yds = '5.8';
    //     elseif ($route['grade'] == '5c'||$route['grade'] == '5c+') $grade_yds = '5.9';
    //     elseif ($route['grade'] == '6a') $grade_yds = '5.10a';
    //     elseif ($route['grade'] == '6a+') $grade_yds = '5.10b';
    //     elseif ($route['grade'] == '6b') $grade_yds = '5.10c';
    //     elseif ($route['grade'] == '6b+') $grade_yds = '5.10d';
    //     elseif ($route['grade'] == '6c') $grade_yds = '5.11a/5.11b';
    //     elseif ($route['grade'] == '6c+') $grade_yds = '5.11c';
    //     elseif ($route['grade'] == '7a') $grade_yds = '5.11d';
    //     elseif ($route['grade'] == '7a+') $grade_yds = '5.12a';
    //     elseif ($route['grade'] == '7b') $grade_yds = '5.12b';
    //     elseif ($route['grade'] == '7b+') $grade_yds = '5.12c';
    //     elseif ($route['grade'] == '7c') $grade_yds = '5.12d';
    //     elseif ($route['grade'] == '7c+') $grade_yds = '5.13a';
    //     elseif ($route['grade'] == '8a') $grade_yds = '5.13b';
    //     elseif ($route['grade'] == '8a+') $grade_yds = '5.13c';
    //     elseif ($route['grade'] == '8b') $grade_yds = '5.13d';
    //     elseif ($route['grade'] == '8b+') $grade_yds = '5.14a';
    //     elseif ($route['grade'] == '8c') $grade_yds = '5.14b';
    //     elseif ($route['grade'] == '8c+') $grade_yds = '5.14c';
    //     elseif ($route['grade'] == '9a') $grade_yds = '5.14d';
    //     elseif ($route['grade'] == '9a+') $grade_yds = '5.15a';
    //     elseif ($route['grade'] == '9b') $grade_yds = '5.15b';
    //     elseif ($route['grade'] == '9b+') $grade_yds = '5.15c';
    //     elseif ($route['grade'] == '9c') $grade_yds = '5.15d';
    //     elseif ($route['grade'] == '9c+') $grade_yds = '5.16a';
    //     else $grade_yds = '?';

    //     return $grade_yds;
    // }

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

    public function del_sector_image_from_db(Request $request)
    {
        $image = Sector_image::where('id', '=', $request->image_id)->first();
        ImageControllService::image_delete('images/sector_img/', $image, 'image');
        $image ->delete();
    }

    // public function get_routes_for_model(Request $request)
    // {
    //     $routes = Route::where('sector_id',strip_tags($request->sector_id))->orderBy('num')->get();
    //     return( $routes );
    // }

	// public function get_mtp_for_model(Request $request)
    // {
    //     $mtps = Mtp::where('sector_id',strip_tags($request->sector_id))->orderBy('num')->get();
    //     return( $mtps );
    // }

	// public function get_mtp_pitchs_for_model(Request $request)
    // {
    //     $mtp_pitchs = Mtp_pitch::where('mtp_id',strip_tags($request->mtp_id))->orderBy('num')->get();
    //     return( $mtp_pitchs );
    // }

	public function get_sector_data_for_model(Request $request)
    {
        $mtps = Mtp::where('sector_id',strip_tags($request->sector_id))->orderBy('num')->get();
        $routes = Route::where('sector_id',strip_tags($request->sector_id))->orderBy('num')->get();
        $images = Sector_image::where('sector_id',strip_tags($request->sector_id))->orderBy('num')->get();
        $data = [
            'images' => $images,
            'routes' => $routes,
            'mtps' => $mtps,
        ];
        
        return( $data );
    }

    public function get_sector_editing_data(Request $request)
    {
        $sector = Sector::where('id',strip_tags($request->sector_id))->first();
        $data = [
            'sector' => $sector,
            'images' => $sector->images,
        ];
        return $data;
    }

    public function get_sector_images(Request $request)
    {
        $sector = Sector::where('id',strip_tags($request->sector_id))->first();
        return $sector->images;
    }
}

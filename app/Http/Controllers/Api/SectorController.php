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
// use App\Models\Comment;
// use App\Models\Gallery;

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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pitch_num_in_array = 0;
        $mtp_num_in_array = 0;
        $area_info = array();
        $route_info = array();
        $sector_imgs = array();
        $mtp_info = array();
        $mtp_pitch_info = array();

        $route_num = 0;
        $mtp_pitch_num = 0;

        // $id = $request->region_id;

        $sector_count = Sector::where('article_id', '=', $id)->count();
        if ($sector_count > 0) {
            $sector_n = Sector::where('article_id', '=', $id)->orderBy('num')->get();
            foreach($sector_n as $sector){
                $sectors_img_count = Sector_image::where('sector_id', '=', $sector->id)->count();
                if ($sectors_img_count > 0) {
                    $sectors_img = Sector_image::limit(10)->where('sector_id', '=', $sector->id)->orderBy('num')->get();
                    // $sectors_img_ouent = Sector_image::where('sector_id', '=', $sector->id)->orderBy('num')->count();
                    // dd($sectors_img_ouent);

                    // $sector_img_size = 100;
                    // if($sectors_img_ouent > 1){
                    //     $sector_img_size = 100 / $sectors_img_ouent;
                    //     $sector_img_size = $sector_img_size - 1;
                    // }

                    $sector_imgs = array();
                    foreach($sectors_img as $sector_img){
                        array_push($sector_imgs, 
                            array(
                                'id' => $sector_img->id,
                                'image' => $sector_img->image,
                                // 'sector_img_size'=>$sector_img_size,
                                )   
                        );
                    }
                }
                else $sector_imgs = array();

                $routes_count = Route::where('sector_id', '=', $sector->id)->count();
                if ($routes_count > 0){
                    $routes = Route::where('sector_id', '=', $sector->id)->orderBy('num')->get();
                    $route_info = array();
                    foreach($routes as $x=>$route){
                        $grade_yds = 0;
                        if ($route['grade'] != NULL) {
                            $grade_yds = $this->get_yds_grade($route);
                        }
                        $or_grade_yds = 0;
                        if ($route['or_grade'] != NULL) {
                            $or_grade_yds = $this->get_yds_grade($route);
                        }
                        $route_num++;
                        array_push($route_info, 
                            array(
                                "id"=>$route['id'], 
                                "num"=>$route_num,
                                "name"=>$route['name'], 
                                "text"=>$route['text'], 
                                "height"=>$route['height'], 
                                "bolts"=>$route['bolts'], 
                                "grade_fr"=>$route['grade'],
                                "or_grade_fr"=>$route['or_grade'],
                                "grade_yds"=>$grade_yds,
                                "or_grade_yds"=>$or_grade_yds,
                                "last_carabin"=>$route['last_carabin'], 
                                "first_ascent"=>$route['first_ascent'], 
                                "bolter"=>$route['bolter'], 
                                "category"=>$route['category'], 
                                "dolting_data"=>$route['dolting_data'], 
                                "stars"=>$route['stars'],
                            )
                        );
                    }
                    // dd($route_info);
                    $route_num = 0;
                }
                else $route_info = array();

                $mtps_count = MTP::where('sector_id', '=', $sector->id)->count();
                if ($mtps_count > 0){
                    $mtps = MTP::where('sector_id', '=', $sector->id)->orderBy('num')->get();
                    $mtp_info = array();
                    foreach($mtps as $mtp){
                        $mtp_pitchs = Mtp_pitch::where('mtp_id', '=', $mtp->id)->orderBy('num')->get();
                        $mtp_pitchs_count = Mtp_pitch::where('mtp_id', '=', $mtp->id)->orderBy('num')->count();
                        $pitch_num = 0;
                        $mtp_num_in_array = 0;
                        if ($mtp_pitchs_count > 0) {
                            $mtp_pitch_info = array();
                            foreach($mtp_pitchs as $mtp_pitch){                
                                $pitch_grade_yds = 0;
                                if ($mtp_pitch['grade'] != NULL) {
                                    $pitch_grade_yds = $this->get_yds_grade($mtp_pitch);
                                }
                                $mtp_pitch_num++;
                                array_push($mtp_pitch_info,
                                    [
                                        'pitch_id'=>$mtp_pitch['id'],
                                        'pitch_num'=>$mtp_pitch_num,
                                        'pitch_name'=>$mtp_pitch['name'],
                                        "pitch_text"=>$mtp_pitch['text'], 
                                        "pitch_height"=>$mtp_pitch['height'], 
                                        "pitch_bolts"=>$mtp_pitch['bolts'], 
                                        "pitch_grade_fr"=>$mtp_pitch['grade'],
                                        "pitch_grade_yds"=>$pitch_grade_yds,
                                        "pitch_first_ascent"=>$mtp_pitch['first_ascent'], 
                                        "pitch_bolter"=>$mtp_pitch['bolter'], 
                                        "pitch_dolting_data"=>$mtp_pitch['dolting_data'],
                                    ]
                                );
                            }
                            $mtp_pitch_num = 0;
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
                    // 'sectors_img_ouent'=>$sectors_img_ouent,

                    "all_day_in_shade"=>$sector->all_day_in_shade,
                    "all_day_in_sun"=>$sector->all_day_in_sun,
                    "in_the_shade_afternoon"=>$sector->in_the_shade_afternoon,
                    "in_the_shade_befornoon"=>$sector->in_the_shade_befornoon,
                    "in_shade_after_10"=>$sector->in_shade_after_10,
                    "in_shade_after_15"=>$sector->in_shade_after_15,

                    "overhang"=>$sector->overhang,
                    "slabby"=>$sector->slabby,
                    "vertical"=>$sector->vertical,

                    'sector_img'=>$sector_imgs,
                );
                
                array_push($area_info, 
                    array(
                        "sectors" => $sector_info,  
                        "routes" => $route_info,
                        "mtps" => $mtp_info
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request -> except('_token');

            $this->sector_validate($request);
            // dd($request);

            // $sectors = sector::where("article_id","=",$input["article_id"])->get("num");
            // $sectors_count = sector::where("article_id","=",$input["article_id"])->get("num")->count();
            // if($sectors_count!=0){
            //     $sector_num_array = array();
            //     foreach($sectors as $sector){
            //         array_push($sector_num_array, $sector->num);
            //     }
            //     $sector_highst_num = max($sector_num_array);
            //     $new_sector_num = $sector_highst_num + 1;
            //     $input['num'] = $new_sector_num;
            // }
            // else{
            //     $input['num'] = 1;
            // }
            // $sector = new sector();
            // $sector -> fill($input);
            $sectors = sector::where("name","=","temporary_sector")->get();
            foreach ($sectors as $sectors) {
                $last_sector_id = $sectors->id;
            }

            $sector = sector::find($last_sector_id);
            // $sector = new sector();

            $sector['article_id'] = $request->article_id;
            $sector['name'] = $request->name;
            $sector['text'] = $request->text;
            $sector['all_day_in_shade'] = $request->all_day_in_shade;
            $sector['all_day_in_sun'] = $request->all_day_in_sun;
            $sector['in_the_shade_afternoon'] = $request->in_the_shade_afternoon;
            $sector['in_the_shade_befornoon'] = $request->in_the_shade_befornoon;
            $sector['in_shade_after_10'] = $request->in_shade_after_10;
            $sector['in_shade_after_15'] = $request->in_shade_after_15;
            $sector['slabby'] = $request->slabby;
            $sector['vertical'] = $request->vertical;
            $sector['overhang'] = $request->overhang;

            $sector -> save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sector_id=$id;

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






    public function sector_image_validate($request)
    {
        $request->validate([
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    }

    private function sector_validate($request)
    {
        $request->validate([
            'name' => 'required|max:190',
            'article_id' => 'required'
        ]);
    }

    public static function get_yds_grade($route)
    {
        if ($route['grade'] == '4') $grade_yds = '5.6';
        elseif ($route['grade'] == '5a'||$route['grade'] == '5a+') $grade_yds = '5.7';
        elseif ($route['grade'] == '5b'||$route['grade'] == '5b+') $grade_yds = '5.8';
        elseif ($route['grade'] == '5c'||$route['grade'] == '5c+') $grade_yds = '5.9';
        elseif ($route['grade'] == '6a') $grade_yds = '5.10a';
        elseif ($route['grade'] == '6a+') $grade_yds = '5.10b';
        elseif ($route['grade'] == '6b') $grade_yds = '5.10c';
        elseif ($route['grade'] == '6b+') $grade_yds = '5.10d';
        elseif ($route['grade'] == '6c') $grade_yds = '5.11a/5.11b';
        elseif ($route['grade'] == '6c+') $grade_yds = '5.11c';
        elseif ($route['grade'] == '7a') $grade_yds = '5.11d';
        elseif ($route['grade'] == '7a+') $grade_yds = '5.12a';
        elseif ($route['grade'] == '7b') $grade_yds = '5.12b';
        elseif ($route['grade'] == '7b+') $grade_yds = '5.12c';
        elseif ($route['grade'] == '7c') $grade_yds = '5.12d';
        elseif ($route['grade'] == '7c+') $grade_yds = '5.13a';
        elseif ($route['grade'] == '8a') $grade_yds = '5.13b';
        elseif ($route['grade'] == '8a+') $grade_yds = '5.13c';
        elseif ($route['grade'] == '8b') $grade_yds = '5.13d';
        elseif ($route['grade'] == '8b+') $grade_yds = '5.14a';
        elseif ($route['grade'] == '8c') $grade_yds = '5.14b';
        elseif ($route['grade'] == '8c+') $grade_yds = '5.14c';
        elseif ($route['grade'] == '9a') $grade_yds = '5.14d';
        elseif ($route['grade'] == '9a+') $grade_yds = '5.15a';
        elseif ($route['grade'] == '9b') $grade_yds = '5.15b';
        elseif ($route['grade'] == '9b+') $grade_yds = '5.15c';
        elseif ($route['grade'] == '9c') $grade_yds = '5.15d';
        elseif ($route['grade'] == '9c+') $grade_yds = '5.16a';
        else $grade_yds = '?';

        return $grade_yds;
    }
}

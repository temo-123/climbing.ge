<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Sector;
use App\Models\Route;
use App\Models\Mtp;
use App\Models\Comment;
use App\Models\Gallery;
use App\Models\Sector_image;
use App\Models\Mtp_pitch;

use App\Services\GetArticlesService;

class OutdoorController extends Controller
{
    public function outdoor_list()
    {
    	if (view()->exists('site.outdoor_list')) {
            $global_outdoors = Article::latest('id')->where('category', '=', 'outdoor')->where('published', '=', 1)->get();
            $article_count = Article::latest('id')->where('category', '=', 'outdoor')->where('published', '=', 1)->count();
            
            $outdoors = GetArticlesService::get_locale_article($global_outdoors);
            $time_array = GetArticlesService::get_new_article_pin($outdoors);

            $route_num = 0;
            $mtp_num = 0;
            $route_quantity = array();
            foreach($global_outdoors as $outdoor){
                $sector_n = Sector::where('article_id', '=', $outdoor->id)->get();
                $routes_a = array ($outdoor->title);
                $mtps_a = array ();
                $sector_count = Sector::where('article_id', '=', $outdoor->id)->count();
                foreach($sector_n as $sector){
                    $routes = Route::where('sector_id', '=', $sector->id)->count();
                    foreach((array) $routes as $route){
                        $route_num++;
                        array_push($routes_a, $route);
                    }
                    $mtps = MTP::where('sector_id', '=', $sector->id)->count();
                    if ($mtps > 0) {
                        foreach((array) $mtps as $mtp){
                            $mtp_num++;
                            array_push($mtps_a, $mtp);
                        }
                    }
                }
                if($route_num == $sector_count) {
                    $route_sum=array_sum($routes_a);
                    $mtp_sum=array_sum($mtps_a);
                    array_push($route_quantity, array($outdoor->url_title, $sector_count, "Sectors", $route_sum, "Route", $mtp_sum, "MTP") ); // push route num in last array
                }
                else $route_sum=0;{
                    $route_num = 0;
                }
            }

            $data = [
                'title'=>'Outdoor Climbing',
                'article_list'=>$outdoors,
                'article_count'=>$article_count,
                'outdoor_climbing'=>1,
                'time_array' => $time_array,
                
                'num_1'=>1,
                'num_2'=>1,
                'num_3'=>1,
                'route_num'=>1,

                'route_quantity'=>$route_quantity,
                
                'num'=>1,
                'articles_link' => 'outdoor_page',
                'image_dir' => 'outdoor_img',
                
                'meta_title' => 'Outdoor climbing regions in Georgia',
                'meta_description' => 'In Georgia are many outdoor climbing zone. You can see the full information about these regions and visit one of them.',
                'meta_img' => 'outdoor.jpg'
            ];
            return view('site.outdoor_list',$data);
        }
        abort(404);
    }

    public function outdoor_page($name)
    {
        if (!$name){abort(404);}
        
        if (view()->exists('site.outdoor_page')) {
            $global_outdoors = Article::latest('id')->where('category', '=', 'outdoor')->where('url_title',strip_tags($name))->first();
            $id = $global_outdoors->id;
            $outdoors = GetArticlesService::get_locale_article_in_page($global_outdoors);

            $comments = Comment::where('article_id',strip_tags($id))->get();
            // $article_gallery = gallery::where('article_id',strip_tags($id))->where('published','=','1')->where('category','=','article_image')->limit(8)->get();
            // $article_gallery_count = gallery::where('article_id',strip_tags($id))->where('published','=','1')->where('category','=','article_image')->count();

            $global_other_list = Article::latest('id')->where('category', '=', 'outdoor')->inRandomOrder()->where('published','=','1')->limit(6)->get();
            $other_list = GetArticlesService::get_locale_article($global_other_list);
            
            $data  = [
                'outdoor_climbing' => 0,
                'title'=>$outdoors[0][0]['title'],
                'article'=>$outdoors[0],

                // 'area_info'=>$area_info,
                'mtp_rope_index'=>0,
                'mtp_index'=>0,

                // 'articles_gallery'=>$article_gallery,
                // 'article_gallery_count'=>$article_gallery_count,
                'slider_link'=>'../../assets/img/outdoor_img/slider_img/',
                'all_article_but'=>'outdoor_list',
                'article_map'=>'Outdoor',
                'view_360'=>1,
                'what_need'=>1,
                
                'image' => 'outdoor_img/'.$outdoors[0]['image'],
                
                'article_edit_link'=>'outdoorEdit',

                'other_article'=>$other_list,
                'other_article_link'=>'outdoor_page',
                'other_article_img'=>'images/outdoor_img/',
                
                'sport_route_sector' => 1,
                
                'comments'=>$comments,
            ];
            return view('site.outdoor_page',$data);
        }
        else abort(404);
    }

    public function get_sectors_and_routes(Request $request)
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

        $id = $request->region_id;

        $sector_count = Sector::where('article_id', '=', $id)->count();
        if ($sector_count > 0) {
            $sector_n = Sector::where('article_id', '=', $id)->orderBy('num')->get();
            foreach($sector_n as $sector){
                $sectors_img_count = Sector_image::where('sector_id', '=', $sector->id)->count();
                if ($sectors_img_count > 0) {
                    $sectors_img = Sector_image::where('sector_id', '=', $sector->id)->orderBy('num')->get();
                    $sectors_img_ouent = Sector_image::where('sector_id', '=', $sector->id)->orderBy('num')->count();
                    // dd($sectors_img_ouent);

                    $sector_img_size = 100;
                    if($sectors_img_ouent > 1){
                        $sector_img_size = 100 / $sectors_img_ouent;
                        $sector_img_size = $sector_img_size - 1;
                    }

                    $sector_imgs = array();
                    foreach($sectors_img as $sector_img){
                        array_push($sector_imgs, 
                            array(
                                'image' => $sector_img->image,
                                'sector_img_size'=>$sector_img_size,
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
                    'sectors_img_ouent'=>$sectors_img_ouent,

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


    public static function get_region_image(Request $request){
        $region_image = Article::where('id',strip_tags($request->region_id))->get('climbing_area_image');
        return $region_image;
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

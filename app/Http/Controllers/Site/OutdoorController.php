<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Models\Article;

class OutdoorController extends Controller
{
    public function outdoor_list()
    {
    	if (view()->exists('site.outdoor_list')) {
            $outdoors = Article::latest('id')->where('category', '=', 'outdoor')->where('published', '=', 1)->get();
            $article_count = Article::latest('id')->where('category', '=', 'outdoor')->where('published', '=', 1)->count();
            
            $route_num = 0;
            $mtp_num = 0;
            $route_quantity = array();
            foreach($outdoors as $outdoor){
                $sector_n = \App\Sector::where('article_id', '=', $outdoor->id)->get();
                $routes_a = array ($outdoor->title);
                $mtps_a = array ();
                $sector_count = \App\Sector::where('article_id', '=', $outdoor->id)->count();
                foreach($sector_n as $sector){
                    $routes = \App\Route::where('sector_id', '=', $sector->id)->count();
                    foreach((array) $routes as $route){
                        $route_num++;
                        array_push($routes_a, $route);
                    }
                    $mtps = \App\MTP::where('sector_id', '=', $sector->id)->count();
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
                    array_push($route_quantity, array($outdoor->title, $sector_count, "Sectors", $route_sum, "Route", $mtp_sum, "MTP") ); // push route num in last array
                }
                else $route_sum=0;{
                    $route_num = 0;
                }
            }

            // foreach($route_quantity as $route_num){
            //     print_r($route_num[0]);
            // }
            // dd($route_quantity); // echo array 'route quantity'
            $time_array = array();
            foreach ($outdoors as $outdoor) {
                if ($outdoor->created_at->lt(Carbon::now()->subDays(30))){
                    $time = 0;
                    array_push($time_array, ['id'=>$outdoor->id, 'name'=>$outdoor->title, 'time'=>$time]);
                }
                else {
                    $time = 1;
                    array_push($time_array, ['id'=>$outdoor->id, 'name'=>$outdoor->title, 'time'=>$time]);
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
                
                // Meta teg
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
            $outdoors = Article::latest('id')->where('category', '=', 'outdoor')->where('url_title',strip_tags($name))->first();
            $id = $outdoors->id;

            $comments = \App\Comments::where('article_id',strip_tags($id))->get();
            $article_gallery = \App\galleries::where('article_id',strip_tags($id))->where('published','=','1')->where('category','=','article_image')->limit(8)->get();
            $article_gallery_count = \App\galleries::where('article_id',strip_tags($id))->where('published','=','1')->where('category','=','article_image')->count();
            $other_list = Article::latest('id')->where('category', '=', 'outdoor')->inRandomOrder()->where('published','=','1')->limit(6)->get();


            $pitch_num_in_array = 0;
            $mtp_num_in_array = 0;
            $sector_count = \App\Sector::where('article_id', '=', $id)->count();
            if ($sector_count > 0) {
                $sector_n = \App\Sector::where('article_id', '=', $id)->orderBy('num')->get();
                $area_info = array();
                foreach($sector_n as $sector){

                    $sectors_img_count = \App\Sector_image::where('sector_id', '=', $sector->id)->count();
                    if ($sectors_img_count > 0) {
                        $sectors_img = \App\Sector_image::where('sector_id', '=', $sector->id)->orderBy('num')->get();
                        $sector_imgs = array();
                        foreach($sectors_img as $sector_img){
                            array_push($sector_imgs, 
                                array("name"=>$sector_img['image']));
                        }
                    }
                    else $sector_imgs = NULL;

                    $routes_count = \App\Route::where('sector_id', '=', $sector->id)->count();
                    if ($routes_count > 0){
                        $routes = \App\Route::where('sector_id', '=', $sector->id)->orderBy('num')->get();
                        $route_info = array();
                        foreach($routes as $x=>$route){
                            $grade_yds = 0;
                            if ($route['grade'] != NULL) {
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
                                elseif ($route['style'] == 'Aid Climbing') $grade_yds = '?';
                                else $grade_yds = '?';
                            }
                            array_push($route_info, 
                                array(
                                    "name"=>$route['name'], 
                                    "text"=>$route['text'], 
                                    "height"=>$route['height'], 
                                    "bolts"=>$route['bolts'], 
                                    "grade_fr"=>$route['grade'],
                                    "grade_yds"=>$grade_yds,
                                    "last_carabin"=>$route['last_carabin'], 
                                    "first_ascent"=>$route['first_ascent'], 
                                    "bolter"=>$route['bolter'], 
                                    "dolting_data"=>$route['dolting_data'], 
                                    "stars"=>$route['stars'],

                                    "style"=>$route['style'],
                                )
                            );
                        }
                    }
                    else $route_info = NULL;

                    $mtps_count = \App\MTP::where('sector_id', '=', $sector->id)->count();
                    if ($mtps_count > 0){
                        $mtps = \App\MTP::where('sector_id', '=', $sector->id)->orderBy('num')->get();
                        $mtp_info = array();
                        foreach($mtps as $mtp){
                            array_push($mtp_info, 
                                array(
                                    "mtp name"=>$mtp['name'],
                                    "mtp text"=>$mtp['text'],
                                    "mtp pitchs quantity"=> [],
                                    "mtp pitchs"=> [],
                                )
                            );
                            $mtp_pitchs = \App\Mtp_pitchs::where('mtp_id', '=', $mtp->id)->orderBy('num')->get();
                            $pitch_num = 0;
                            foreach($mtp_pitchs as $mtp_pitch){                            
                                $pitch_grade_yds = 0;
                                if ($mtp_pitch['grade'] != NULL) {
                                    if ($mtp_pitch['grade'] == '4') $pitch_grade_yds = '5.6';
                                    elseif ($mtp_pitch['grade'] == '5a'||$mtp_pitch['grade'] == '5a+') $pitch_grade_yds = '5.7';
                                    elseif ($mtp_pitch['grade'] == '5b'||$mtp_pitch['grade'] == '5b+') $pitch_grade_yds = '5.8';
                                    elseif ($mtp_pitch['grade'] == '5c'||$mtp_pitch['grade'] == '5c+') $pitch_grade_yds = '5.9';
                                    elseif ($mtp_pitch['grade'] == '6a') $pitch_grade_yds = '5.10a';
                                    elseif ($mtp_pitch['grade'] == '6a+') $pitch_grade_yds = '5.10b';
                                    elseif ($mtp_pitch['grade'] == '6b') $pitch_grade_yds = '5.10c';
                                    elseif ($mtp_pitch['grade'] == '6b+') $pitch_grade_yds = '5.10d';
                                    elseif ($mtp_pitch['grade'] == '6c') $pitch_grade_yds = '5.11a/5.11b';
                                    elseif ($mtp_pitch['grade'] == '6c+') $pitch_grade_yds = '5.11c';
                                    elseif ($mtp_pitch['grade'] == '7a') $pitch_grade_yds = '5.11d';
                                    elseif ($mtp_pitch['grade'] == '7a+') $pitch_grade_yds = '5.12a';
                                    elseif ($mtp_pitch['grade'] == '7b') $pitch_grade_yds = '5.12b';
                                    elseif ($mtp_pitch['grade'] == '7b+') $pitch_grade_yds = '5.12c';
                                    elseif ($mtp_pitch['grade'] == '7c') $pitch_grade_yds = '5.12d';
                                    elseif ($mtp_pitch['grade'] == '7c+') $pitch_grade_yds = '5.13a';
                                    elseif ($mtp_pitch['grade'] == '8a') $pitch_grade_yds = '5.13b';
                                    elseif ($mtp_pitch['grade'] == '8a+') $pitch_grade_yds = '5.13c';
                                    elseif ($mtp_pitch['grade'] == '8b') $pitch_grade_yds = '5.13d';
                                    elseif ($mtp_pitch['grade'] == '8b+') $pitch_grade_yds = '5.14a';
                                    elseif ($mtp_pitch['grade'] == '8c') $pitch_grade_yds = '5.14b';
                                    elseif ($mtp_pitch['grade'] == '8c+') $pitch_grade_yds = '5.14c';
                                    elseif ($mtp_pitch['grade'] == '9a') $pitch_grade_yds = '5.14d';
                                    elseif ($mtp_pitch['grade'] == '9a+') $pitch_grade_yds = '5.15a';
                                    elseif ($mtp_pitch['grade'] == '9b') $pitch_grade_yds = '5.15b';
                                    elseif ($mtp_pitch['grade'] == '9b+') $pitch_grade_yds = '5.15c';
                                    elseif ($mtp_pitch['grade'] == '9c') $pitch_grade_yds = '5.15d';
                                    elseif ($mtp_pitch['grade'] == '9c+') $pitch_grade_yds = '5.16a';
                                    elseif ($mtp_pitch['style'] == 'Aid Climbing') $pitch_grade_yds = '?';
                                    else $pitch_grade_yds = '?';
                                }
                                $mtp_pitch_info = array(
                                    'pitch name'=>$mtp_pitch['name'],
                                    "pitch text"=>$mtp_pitch['text'], 
                                    "pitch height"=>$mtp_pitch['height'], 
                                    "pitch bolts"=>$mtp_pitch['bolts'], 
                                    "pitch grade fr"=>$mtp_pitch['grade'],
                                    "pitch grade yds"=>$pitch_grade_yds,
                                    "pitch first_ascent"=>$mtp_pitch['first_ascent'], 
                                    "pitch bolter"=>$mtp_pitch['bolter'], 
                                    "pitch dolting_data"=>$mtp_pitch['dolting_data'],

                                    "pitch style"=>$mtp_pitch['style'],
                                );
                                array_push($mtp_info[$pitch_num_in_array]["mtp pitchs"],$mtp_pitch_info);
                                $pitch_num++;
                            }
                            array_push($mtp_info[$mtp_num_in_array]['mtp pitchs quantity'],$pitch_num);
                            $mtp_num_in_array++;
                            $pitch_num_in_array++;
                        }
                    }
                    else $mtp_info = NULL;

                    $sector_info = array(
                        "name"=>$sector->name, 
                        "text"=>$sector->text, 
                        'sector_img'=>$sector_imgs
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

            // dd($sector_imgs);
            // dd($area_info);

            $data  = [
                'outdoor_climbing' => 0,
                'title'=>$outdoors->name,
                'article'=>$outdoors,

                'area_info'=>$area_info,
                'mtp_rope_index'=>0,
                'mtp_index'=>0,

                'articles_gallery'=>$article_gallery,
                'article_gallery_count'=>$article_gallery_count,
                'slider_link'=>'../../assets/img/outdoor_img/slider_img/',
                'all_article_but'=>'outdoor_list',
                'article_map'=>'Outdoor',
                'view_360'=>1,
                'what_need'=>1,
                'image_dir' => 'outdoor_img',
                
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
}

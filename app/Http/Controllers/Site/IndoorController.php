<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Gallery;
use App\Models\Comment;
use App\Models\Star_review;

use App\Services\GetArticlesService;

class IndoorController extends Controller
{
    public function indoor_list() {
    	if (view()->exists('site.indoor_list')) {
    		$global_indoors = Article::latest('id')->where('category', '=', 'indoor')->where('published', '=', 1)->get();
            $indoors = GetArticlesService::get_locale_article($global_indoors);
            $article_count = Article::latest('id')->where('category', '=', 'indoor')->where('published', '=', 1)->count();

            $users_review = Star_review::where('category', '=', 'article_review')->where('review_value', '=', '2')->get();
            $users_review_count = Star_review::where('category', '=', 'article_review')->where('review_value', '=', '2')->count();
            $admins_review = Star_review::where('category', '=', 'article_review')->where('review_value', '=', '1')->get();
            $admins_review_count = Star_review::where('category', '=', 'article_review')->where('review_value', '=', '1')->count();
            $review_count = Star_review::where('category', '=', 'article_review')->count();

            $user_only_review_array = array();
            $admin_only_review_array = array();
            $total_review_array = array();

            $review_array = array();

            $user_review_array = array();
            $user_review_total_array = array();
            $total_user_review_array = array();

            $admin_review_array = array();
            $admin_review_total_array = array();
            $total_admin_review_array = array();

            $num_1 = 0;
            $num_2 = 0;

            if ($review_count > 0) {
                foreach ($indoors as $indoor) {
                    if ($users_review_count != 0) {  
                        foreach ($users_review as $user_review) {
                            if ($indoor -> id == $user_review -> article_id) array_push($user_only_review_array, $user_review->stars);
                            $user_loop_num = count($user_only_review_array);
                            if ($user_loop_num != 0 && $empty_array = 1) {
                                $user_review_array_sum = array_sum($user_only_review_array);
                                $user_review_total_big = $user_review_array_sum / $user_loop_num;
                                $user_review_total = round($user_review_total_big, 1);
                            }
                            else $user_review_total = 0;
                        }
                        array_push($user_review_total_array, ["article_title" => $indoor->url_title, "article_id" => $indoor->id, "user_review" => $user_review_total]);
                        $user_only_review_array = array();
                    }
                    else array_push($user_review_total_array, ["article_title" => $indoor->url_title, "article_id" => $indoor->id, "user_review" => 0]);
                    if ($admins_review_count != 0) {
                        foreach ($admins_review as $admin_review) {
                            if ($indoor -> id == $admin_review -> article_id) array_push($admin_only_review_array, $admin_review->stars);
                            $admin_loop_num = count($admin_only_review_array);
                            if ($admin_loop_num != 0) {
                                $admin_review_array_sum = array_sum($admin_only_review_array);
                                $admin_review_total_big = $admin_review_array_sum / $admin_loop_num;
                                $admin_review_total = round($admin_review_total_big, 1);
                            }
                            else $admin_review_total = 0;
                        }
                        array_push($admin_review_total_array, ["article_title" => $indoor->url_title, "article_id" => $indoor->id, "admin_review" => $admin_review_total]);
                        $admin_only_review_array = array();
                    }
                    else array_push($admin_review_total_array, ["article_title" => $indoor->url_title, "article_id" => $indoor->id, "admin_review" => 0]);

                    if ($user_review_total_array[$num_1]["article_title"] == $indoor->url_title && $admin_review_total_array[$num_1]["article_title"] == $indoor->url_title) {
                        if ($user_review_total_array[$num_1]["user_review"] != 0 && $admin_review_total_array[$num_1]["admin_review"] != 0) {
                            $review_sum = ($user_review_total_array[$num_1]["user_review"] + $admin_review_total_array[$num_1]["admin_review"]) / 2;
                            array_push($review_array, ["article_title" => $user_review_total_array[$num_1]["article_title"], "review" =>$review_sum]);
                        }
                        if ($user_review_total_array[$num_1]["user_review"] == 0 && $admin_review_total_array[$num_1]["admin_review"] != 0) array_push($review_array, ["article_title" => $admin_review_total_array[$num_1]["article_title"], "review" =>$admin_review_total_array[$num_1]["admin_review"]]);
                        if ($user_review_total_array[$num_1]["user_review"] != 0 && $admin_review_total_array[$num_1]["admin_review"] == 0) array_push($review_array, ["article_title" => $user_review_total_array[$num_1]["article_title"], "review" =>$user_review_total_array[$num_1]["user_review"]]);
                        if ($user_review_total_array[$num_1]["user_review"] == 0 && $admin_review_total_array[$num_1]["admin_review"] == 0) array_push($review_array, ["article_title" => $user_review_total_array[$num_1]["article_title"], "review" => 0]);
                    }
                    $num_1++;
                }
            }
            array_push($total_review_array, ["user_review"=>$user_review_total_array, "admin_review"=>$admin_review_total_array]);

            // dd($total_review_array[0], $review_array);

    		$data = [
    			'title'=>'Indoor Climbing',
    			'indoors'=>$indoors,
                'article_count'=>$article_count,
    			'indoor_climbing'=>1,

                'reviews'=>$review_array,
                'num'=>$num_2,
    		];
    		return view('site.indoor_list',$data);
    	}
    	abort(404);
    }

    public function indoor_page($name) {
        if (!$name) {
            abort(404);
        }
        if (view()->exists('site.indoor_page')) {
            $global_indoor = Article::latest('id')->where('category', '=', 'indoor')->where('url_title',strip_tags($name))->first();

            $indoor_id = $global_indoor->id;

            $indoor = GetArticlesService::get_locale_article_in_page($global_indoor);

            $global_other_list = Article::latest('id')->where('category', '=', 'indoor')->inRandomOrder()->where('published','=','1')->limit(6)->get();
            $other_list = GetArticlesService::get_locale_article($global_other_list);
            
            $article_gallery = Gallery::where('article_id',strip_tags($indoor_id))->get();

            $article_gallery = Gallery::where('article_id',strip_tags($indoor_id))->limit(8)->get();
            $comments = Comment::where('article_id',strip_tags($indoor_id))->get();
            $data  = [
                'title'=>$indoor[0]->title,
                'article'=>$indoor[0],
                'climbing'=>1,
                'articles_gallery'=>$article_gallery,
                'slider_link'=>'../../assets/img/indoor_img/slider_img/',
                'all_article_but'=>'indoor_list',
                'article_map'=>'indoor',
                
                'image_dir' => 'indoor_img',
                
                'articles_gallery'=>$article_gallery,
                
                'article_edit_link'=>'articlesEdit',

                'other_article'=>$other_list,
                'other_article_link'=>'indoor_page',
                'other_article_img'=>'images/indoor_img/',
                
                'comments'=>$comments,
                'star_rewiev'=>0,
            ];
            
            return view('site.indoor_page',$data);
        }
        else {
            abort(404);
        }
    }
}

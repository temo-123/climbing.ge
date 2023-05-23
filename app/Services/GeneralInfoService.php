<?php

namespace App\Services;

use App\Models\Locale_article;
use App\Models\General_info;
use App\Models\General_info_article;
use App\Models\General_info_event;
// use App\Models\Mount;
use App\Models\Event;

use Carbon\Carbon;

class GeneralInfoService
{
    public static function add_general_info_relatione($global_blocks, $id, $category)
    {
        if ($category == "article"){
            (new static)->create_block_relatione($global_blocks, $id, 'info_block');
            (new static)->create_block_relatione($global_blocks, $id, 'routes_info');
            (new static)->create_block_relatione($global_blocks, $id, 'what_need_info');
            (new static)->create_block_relatione($global_blocks, $id, 'best_time');
        }
        else if ($category == "event"){
            $general_info_article_info = new General_info_event;

            $general_info_article_info['info_id']=$global_blocks[$block_name."_id"];
            $general_info_article_info['event_id']=$id;
            $general_info_article_info['block']=$block_name;
            $general_info_article_info['block_action']=$global_blocks[$block_name];

            $general_info_article_info -> save();
        }
    }

    private function create_block_relatione($global_blocks, $id, $block_name)
    {
        if($global_blocks[$block_name] != 'new_info'){
            $general_info_article_info = new General_info_article;

            $general_info_article_info['info_id']=$global_blocks[$block_name."_id"];
            $general_info_article_info['article_id']=$id;
            $general_info_article_info['block']=$block_name;
            $general_info_article_info['block_action']=$global_blocks[$block_name];

            $general_info_article_info -> save();
        }
    }


    public static function edit_general_info_relatione($global_blocks, $id, $category)
    {
        if($global_blocks['info_block'] != 'new_info'){

            if($category == "article"){
                (new static)->article_relatione($global_blocks, $id, 'info_block');
                (new static)->article_relatione($global_blocks, $id, 'routes_info');
                (new static)->article_relatione($global_blocks, $id, 'what_need_info');
                (new static)->article_relatione($global_blocks, $id, 'best_time');
            }
            else if ($category == "event"){
                $general_info_relation_count = General_info_event::where('event_id', '=', $id)->count();
                if($general_info_relation_count > 0){
                    $general_info_relation = General_info_event::where('event_id', '=', $id)->first();
                    $general_info_relation['event_id']=Event::where('id', '=', $id)->first()->id;
                    $general_info_relation['info_id']=$global_blocks["info_block_id"];
                    $general_info_relation['block']='info_block';
                    $general_info_relation['block_action']=$global_blocks["info_block"];
    
                    $general_info_relation -> save();
                }
                else{
                    (new static)->create_block_relatione($global_blocks, $id, $category, 'info_block');
                }
            }
            else {
                dd('Category error!');
            }
        }
    }


    private function article_relatione($global_blocks, $article_id, $block_name){
        $general_info_article_info = General_info_article::
                                                        where('article_id', '=', $article_id)->
                                                        where('block', '=', $block_name)->
                                                        first();
        if($global_blocks[$block_name] != 'new_info'){
            $general_info_article_info_count = General_info_article::
                                                        where('article_id', '=', $article_id)->
                                                        where('block', '=', $block_name)->
                                                        count();
                                                        
            if($general_info_article_info_count > 0){
                $general_info_article_info['info_id']=$global_blocks[$block_name."_id"];
                $general_info_article_info['block_action']=$global_blocks[$block_name];

                $general_info_article_info -> save();
            }
            else{
                (new static)->create_block_relatione($global_blocks, $article_id, $block_name);
                // $new_general_info_article_relation = new General_info_article;

                // $new_general_info_article_relation['info_id']=$global_blocks[$block_name."_id"];
                // $new_general_info_article_relation['article_id']=$article_id;
                // $new_general_info_article_relation['block']=$block_name;
                // $new_general_info_article_relation['block_action']=$global_blocks[$block_name];

                // $new_general_info_article_relation -> save();
            }
        }
        else if ($global_blocks[$block_name] == 'new_info' && $general_info_article_info != []){
            $general_info_article_info->delete();
        }
    }
}
<?php

namespace App\Services;

use App\Models\Guide\Locale_article;
use App\Models\Guide\General_info;
use App\Models\Guide\General_info_article;
use App\Models\Guide\General_info_event;
// use App\Models\Guide\Mount;
use App\Models\Guide\Event;

use Carbon\Carbon;

class GeneralInfoService
{
    public static function add_general_info_relatione($global_blocks, $id, $category)
    {
        // dd($global_blocks);
        if ($category == "article"){
            (new static)->create_block_relatione_for_article($global_blocks, $id, 'info_block');
            (new static)->create_block_relatione_for_article($global_blocks, $id, 'routes_info');
            (new static)->create_block_relatione_for_article($global_blocks, $id, 'what_need_info');
            (new static)->create_block_relatione_for_article($global_blocks, $id, 'best_time');
        }
        else if ($category == "event"){
            $general_info_article_info = new General_info_event;

            $general_info_article_info['info_id']=$global_blocks["info_block_id"];
            $general_info_article_info['event_id']=$id;
            $general_info_article_info['block']="info_block";
            $general_info_article_info['block_action']=$global_blocks["info_block"];

            $general_info_article_info -> save();
        }
    }

    private function create_block_relatione_for_article($global_blocks, $id, $block_name)
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

    private function create_block_relatione_for_event($global_blocks, $id){
        $general_info_article_info = new General_info_event;

        $general_info_article_info['info_id']=$global_blocks['info_block_id'];
        $general_info_article_info['event_id']=$id;
        $general_info_article_info['block']='info_block';
        $general_info_article_info['block_action']=$global_blocks['info_block'];

        $general_info_article_info -> save();
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
                // dd('tewst');
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
                    (new static)->create_block_relatione_for_event($global_blocks, $id);
                }
            }
            else {
                dd('General info category error!');
            }
        }
    }

    private function article_relatione($global_blocks, $article_id, $block_name){
        // dd($global_blocks);
        $general_info_article_info = General_info_article::
                                                        where('article_id', '=', $article_id)->
                                                        where('block', '=', $block_name)->
                                                        first();
        // dd($general_info_article_info);
        if($global_blocks[$block_name] != 'new_info' || !$general_info_article_info){
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
                (new static)->create_block_relatione_for_article($global_blocks, $article_id, $block_name);
            }
        }
        else if ($global_blocks[$block_name] == 'new_info' && $general_info_article_info != []){
            $general_info_article_info->delete();
        }
    }

    public static function getGeneralInfoForArticle($global_info, $locale) {
        $general_info = [];

        $info_article_relatione = General_info_article::where('article_id', '=', $global_info->id)->get();

        if ($locale == "us" || $locale == 'en') {
            $locale = "us";
        }

        // dd($info_article_relatione);
        foreach($info_article_relatione as $info){


            if($info->block == 'info_block'){
                $general_info['info_block'] = [
                    "block_action" => $info->block_action,
                    "text"=>General_info::where('id', '=', $info->info_id)->first('text_'.$locale)['text_'.$locale]
                ];
            }
            if($info->block == 'what_need_info'){
                $general_info['what_need_info'] = [
                    "block_action" => $info->block_action,
                    "text"=>General_info::where('id', '=', $info->info_id)->first('text_'.$locale)['text_'.$locale]
                ];
            }
            if($info->block == 'best_time'){
                $general_info['best_time'] = [
                    "block_action" => $info->block_action,
                    "text"=>General_info::where('id', '=', $info->info_id)->first('text_'.$locale)['text_'.$locale]
                ];
            }
            if($info->block == 'routes_info'){
                $general_info['routes_info'] = [
                    "block_action" => $info->block_action,
                    "text"=>General_info::where('id', '=', $info->info_id)->first('text_'.$locale)['text_'.$locale]
                ];
            }
        }

        // dd(General_info::where('id', '=', $info->info_id)->first('text_'.$locale));

        return $general_info;
    }
}
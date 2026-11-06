<?php

namespace App\Services;

use App\Models\Guide\Locale_article;
use App\Models\Guide\General_info;
use App\Models\Guide\General_info_article;
use App\Models\Guide\General_info_event;
// use App\Models\Guide\Mount;
use App\Models\Guide\Event;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            // was saved even for new_info → event pages hid their own info text
            (new static)->create_block_relatione_for_event($global_blocks, $id);
        }
    }

    // A block links to a general_info row only when an action other than new_info
    // is chosen AND a general info is picked (info_id 0 = nothing selected).
    private static function block_is_linked($global_blocks, $block_name)
    {
        $action = $global_blocks[$block_name] ?? 'new_info';
        $info_id = (int) ($global_blocks[$block_name.'_id'] ?? 0);
        return $action !== 'new_info' && $info_id > 0 && General_info::where('id', $info_id)->exists();
    }

    private function create_block_relatione_for_article($global_blocks, $id, $block_name)
    {
        if((new static)->block_is_linked($global_blocks, $block_name)){
            $general_info_article_info = new General_info_article;

            $general_info_article_info['info_id']=$global_blocks[$block_name."_id"];
            $general_info_article_info['article_id']=$id;
            $general_info_article_info['block']=$block_name;
            $general_info_article_info['block_action']=$global_blocks[$block_name];

            $general_info_article_info -> save();
        }
    }

    private function create_block_relatione_for_event($global_blocks, $id){
        if(!(new static)->block_is_linked($global_blocks, 'info_block')) return;

        $general_info_article_info = new General_info_event;

        $general_info_article_info['info_id']=$global_blocks['info_block_id'];
        $general_info_article_info['event_id']=$id;
        $general_info_article_info['block']='info_block';
        $general_info_article_info['block_action']=$global_blocks['info_block'];

        $general_info_article_info -> save();
    }

    // Each block is synced on its own: linked → create / update the relation,
    // new_info (or nothing picked) → remove it so the article's own text is used.
    public static function edit_general_info_relatione($global_blocks, $id, $category)
    {
        $global_blocks = is_array($global_blocks) ? $global_blocks : [];

        if($category == "article"){
            (new static)->article_relatione($global_blocks, $id, 'info_block');
            (new static)->article_relatione($global_blocks, $id, 'routes_info');
            (new static)->article_relatione($global_blocks, $id, 'what_need_info');
            (new static)->article_relatione($global_blocks, $id, 'best_time');
        }
        else if ($category == "event"){
            $general_info_relation = General_info_event::where('event_id', '=', $id)->first();

            if(!(new static)->block_is_linked($global_blocks, 'info_block')){
                if($general_info_relation) $general_info_relation->delete();
            }
            else if($general_info_relation){
                $general_info_relation['info_id']=$global_blocks["info_block_id"];
                $general_info_relation['block']='info_block';
                $general_info_relation['block_action']=$global_blocks["info_block"];

                $general_info_relation -> save();
            }
            else{
                (new static)->create_block_relatione_for_event($global_blocks, $id);
            }
        }
    }

    private function article_relatione($global_blocks, $article_id, $block_name){
        $general_info_article_info = General_info_article::
                                                        where('article_id', '=', $article_id)->
                                                        where('block', '=', $block_name)->
                                                        first();

        if(!(new static)->block_is_linked($global_blocks, $block_name)){
            if($general_info_article_info) $general_info_article_info->delete();
        }
        else if($general_info_article_info){
            $general_info_article_info['info_id']=$global_blocks[$block_name."_id"];
            $general_info_article_info['block_action']=$global_blocks[$block_name];

            $general_info_article_info -> save();
        }
        else{
            (new static)->create_block_relatione_for_article($global_blocks, $article_id, $block_name);
        }
    }

    public static function getGeneralInfoForArticle($global_info, $locale) {
        $general_info = [];

        $info_article_relatione = General_info_article::where('article_id', '=', $global_info->id)->get();

        if ($locale == "us" || $locale == 'en') {
            $locale = "us";
        }

        foreach($info_article_relatione as $info){
            if(!in_array($info->block, ['info_block', 'what_need_info', 'best_time', 'routes_info'], true)) continue;

            // relation to a deleted / missing general info → ignore it
            $general = General_info::where('id', '=', $info->info_id)->first(['text_'.$locale, 'is_show']);
            if(!$general) continue;

            $general_info[$info->block] = [
                "block_action" => $info->block_action,
                "id" => $info->info_id,
                "text" => $general['text_'.$locale],
                "is_show" => $general['is_show'],
            ];
        }

        return $general_info;
    }

    /**
     * Delete a general info record and its related records
     * Handles foreign key constraint violations by cascading deletion
     * 
     * @param int $id The ID of the general info to delete
     * @return bool Success status
     * @throws \Exception If deletion fails
     */
    public static function deleteGeneralInfo($id)
    {
        try {
            DB::beginTransaction();
            
            // Find the general info record
            $generalInfo = General_info::find($id);
            
            if (!$generalInfo) {
                DB::rollBack();
                Log::warning("General info record not found for deletion", ['id' => $id]);
                return false;
            }

            // Delete related records from general_info_article table
            $deletedArticleRelations = General_info_article::where('info_id', $id)->delete();
            Log::info("Deleted {$deletedArticleRelations} article relations for general info", ['general_info_id' => $id]);

            // Delete related records from general_info_event table
            $deletedEventRelations = General_info_event::where('info_id', $id)->delete();
            Log::info("Deleted {$deletedEventRelations} event relations for general info", ['general_info_id' => $id]);

            // Finally delete the general info record
            $deleted = $generalInfo->delete();
            
            if ($deleted) {
                DB::commit();
                Log::info("Successfully deleted general info and related records", ['id' => $id]);
                return true;
            } else {
                DB::rollBack();
                Log::error("Failed to delete general info record", ['id' => $id]);
                return false;
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error deleting general info", [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}

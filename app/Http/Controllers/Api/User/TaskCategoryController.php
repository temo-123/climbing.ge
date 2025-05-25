<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User\Task_category;

class TaskCategoryController extends Controller
{
    function get_all_task_categories(){
        return Task_category::get();
    }

    function get_task_category_data(Request $request){
        return Task_category::where("id", "=", $request->task_category_id)->first();
    }

    function create_task_category(Request $request){
        $new_Task_category = new Task_category;

        $new_Task_category['title'] = $request->data['title'];
        $new_Task_category['short_description'] = $request->data['short_description'];

        $new_Task_category -> save();
    }

    function update_task_category(Request $request){
        $editing_task_category = Task_category::where("id", "=", $request->task_category_id)->first();

        $editing_task_category['title'] = $request->data['title'];
        $editing_task_category['short_description'] = $request->data['short_description'];
        $editing_task_category -> save();
    }

    function del_task_category(Request $request){
        $deleted_user_site = Task_category::where("id", "=", $request->task_category_id)->first();
        $deleted_user_site -> delete();
    }
}

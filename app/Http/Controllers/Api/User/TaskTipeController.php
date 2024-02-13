<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Task_tipe;

class TaskTipeController extends Controller
{
    function get_all_task_tipes(){
        return Task_tipe::get();
    }

    function get_editing_task_tipe(Request $request){
        return Task_tipe::where("id", "=", $request->task_tipe_id)->first();
    }

    function create_task_tipe(Request $request){
        $new_task_tipe = new Task_tipe;

        $new_task_tipe['title'] = $request->data['title'];
        $new_task_tipe['text'] = $request->data['text'];

        $new_task_tipe -> save();
    }

    function update_task_tipe(Request $request){
        $editing_task_tipe = Task_tipe::where("id", "=", $request->task_tipe_id)->first();

        $editing_task_tipe['title'] = $request->data['title'];
        $editing_task_tipe['text'] = $request->data['text'];

        $editing_task_tipe -> save();
    }

    function del_task_tipe(Request $request){
        $deleted_user_site = Task_tipe::where("id", "=", $request->task_tipe_id)->first();
        $deleted_user_site -> delete();
    }
}

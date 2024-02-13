<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;

use App\Models\Task;

class TaskController extends Controller
{
    function get_all_tasks(){
        return Task::get();
    }

    function get_user_tasks(){
        $user = Auth::user();
        if ($user) {
            return Task::where('user_id', '=', $user->id)->get();
        }
        else{
            return responce(421);
        }
    }

    function create_task(Request $request){
        $new_task = new Task;

        $new_task['title'] = $request->data['title'];
        $new_task['text'] = $request->data['text'];
        $new_task['deadline'] = $request->data['deadline'];

        $new_task['category_id'] = $request->data['category_id'];
        $new_task['user_id'] = $request->data['user_id'];

        $new_task -> save();
    }

    function update_task_status(Request $request){
        $editing_task = Task::where("id", "=", $id)->first();

        $editing_task['title'] = $request->data['title'];
        $editing_task['text'] = $request->data['text'];
        $editing_task['deadline'] = $request->data['deadline'];
        
        $editing_task['category_id'] = $request->data['category_id'];
        $editing_task['user_id'] = $request->data['user_id'];

        $editing_task -> save();
    }

    function del_task(Request $request){
        $deleted_user_site = User_site::where("id", "=", $id)->first();
        $deleted_user_site -> delete();
    }
}

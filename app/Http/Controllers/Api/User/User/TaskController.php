<?php

namespace App\Http\Controllers\Api\User\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Notification;

use App\Models\User\Task;

use App\Notifications\task\TaskConfirmationNotificatione;
use App\Notifications\task\TaskCreateNotificatione;

class TaskController extends Controller
{
    function get_all_tasks(){
        return Task::get();
    }

    function get_user_tasks(){
        $user = Auth::user();

        if ($user) {
            return Task::where('from_user_id', '=', $user->id)
                       ->orWhere('for_user_id', '=', $user->id)
                       ->get();
        } else {
            return response(421);
        }
    }

    function get_task_data(Request $request) {
        return Task::where('id', '=', $request->task_id)->first();
    }

    function create_task(Request $request){
        $new_task = new Task;

        $new_task['title'] = $request->data['title'];
        $new_task['text'] = $request->data['text'];
        $new_task['deadline'] = $request->data['deadline'];

        $new_task['status'] = 'set_task';

        $new_task['from_user_id'] = Auth::id();
        $new_task['for_user_id'] = $request->data['for_user_id'];
        $new_task['global_category'] = $request->data['global_category'];
        $new_task['category'] = $request->data['category'];

        $saved = $new_task -> save();
        if($saved){
            // Attach orders or product_options if provided
            if(isset($request->data['order_ids'])){
                $new_task->orders()->attach($request->data['order_ids']);
            }
            if(isset($request->data['product_option_ids'])){
                $new_task->product_options()->attach($request->data['product_option_ids']);
            }

            $info = array(
                'user_email' => $new_task->for_user->email,
            );
            Notification::route('mail', $new_task->for_user->email)->notify(new TaskCreateNotificatione($info));
        }
    }

    function update_task_status(Request $request){
        $editing_task = Task::where("id", "=", $request->task_id)->first();

        $editing_task['status'] = $request->data['status'];
        $editing_task['team_member_comment'] = $request->data['team_member_comment'];

        $editing_task -> save();

        if($editing_task['status'] == 'confirmation_completion'){
            $info = array(
                'user_email' => $editing_task->for_user->email,
            );
            Notification::route('mail', $editing_task->for_user->email)->notify(new TaskConfirmationNotificatione($info));
        }
    }

    function update_task(Request $request){
        $editing_task = Task::where("id", "=", $request->task_id)->first();

        $editing_task['title'] = $request->data['title'];
        $editing_task['text'] = $request->data['text'];
        $editing_task['deadline'] = $request->data['deadline'];

        $editing_task['for_user_id'] = $request->data['for_user_id'];
        $editing_task['global_category'] = $request->data['global_category'];
        $editing_task['category'] = $request->data['category'];

        $editing_task -> save();
    }

    function del_task(Request $request){
        $deleted_user_site = Task::where("id", "=", $request->task_id)->first();
        $deleted_user_site -> delete();
    }
}

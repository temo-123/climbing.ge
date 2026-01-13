<?php

namespace App\Http\Controllers\Api\User;

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

        // dd($user);
        // if ($user) {
            return Task::where('user_id', '=', $user->id)->get();
        // }
        // else{
        //     return responce(421);
        // }
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

        $new_task['category_id'] = $request->data['category_id'];
        $new_task['user_id'] = $request->data['user_id'];

        $saved = $new_task -> save();
        if($saved){
            // dd($new_task->user->email);
            $info = array(  
                'user_email' => $new_task->user->email,
            );
            Notification::route('mail', $new_task->user->email)->notify(new TaskCreateNotificatione($info));
        }
    }

    function update_task_status(Request $request){
        $editing_task = Task::where("id", "=", $request->task_id)->first();

        $editing_task['status'] = $request->data['status'];
        $editing_task['worker_comment'] = $request->data['worker_comment'];

        $editing_task -> save();

        if($editing_task['status'] == 'confirmation_completion'){
            $info = array( 
                'user_email' => $editing_task->user->email,
            );
            Notification::route('mail', $editing_task->user->email)->notify(new TaskConfirmationNotificatione($info));
        }
    }

    function update_task(Request $request){
        $editing_task = Task::where("id", "=", $request->task_id)->first();

        $editing_task['title'] = $request->data['title'];
        $editing_task['text'] = $request->data['text'];
        $editing_task['deadline'] = $request->data['deadline'];
        
        $editing_task['category_id'] = $request->data['category_id'];
        $editing_task['user_id'] = $request->data['user_id'];

        $editing_task -> save();
    }

    function del_task(Request $request){
        $deleted_user_site = Task::where("id", "=", $request->task_id)->first();
        $deleted_user_site -> delete();
    }
}
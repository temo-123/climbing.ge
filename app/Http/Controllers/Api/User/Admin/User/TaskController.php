<?php

namespace App\Http\Controllers\Api\User\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User\Task;

class TaskController extends Controller
{
    public function create_task(Request $request)
    {
        $task = new Task;
        $task->title           = $request->data['title'];
        $task->text            = $request->data['text'] ?? null;
        $task->deadline        = $request->data['deadline'] ?? null;
        $task->status          = 'set_task';
        $task->from_user_id    = Auth::user()->id;
        $task->for_user_id     = $request->data['for_user_id'];
        $task->global_category = $request->data['global_category'] ?? null;
        $task->category        = $request->data['category'] ?? null;
        $task->save();
    }

    public function update_task(Request $request)
    {
        $task = Task::findOrFail($request->task_id);
        $task->title       = $request->data['title'];
        $task->text        = $request->data['text'] ?? null;
        $task->deadline    = $request->data['deadline'] ?? null;
        $task->for_user_id = $request->data['for_user_id'];
        $task->category    = $request->data['category'] ?? null;
        $task->save();
    }

    public function update_task_status(Request $request)
    {
        $task = Task::findOrFail($request->task_id);
        $task->status         = $request->data['status'];
        $task->worker_comment = $request->data['worker_comment'] ?? null;
        $task->save();
    }

    public function del_task(Request $request)
    {
        Task::findOrFail($request->task_id)->delete();
    }
}

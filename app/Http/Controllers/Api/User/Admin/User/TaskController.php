<?php

namespace App\Http\Controllers\Api\User\Admin\User;

use App\Http\Controllers\Controller;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Auth;
use App\Models\User\Task;

class TaskController extends Controller
{
    public function create_task(Request $request)
    {
        if ($auth = PermissionService::authorize('task', 'add')) return $auth;

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
        if ($auth = PermissionService::authorize('task', 'edit')) return $auth;

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
        if ($auth = PermissionService::authorize('task', 'edit')) return $auth;

        $task = Task::findOrFail($request->task_id);
        $task->status         = $request->data['status'];
        $task->worker_comment = $request->data['worker_comment'] ?? null;
        $task->save();
    }

    public function del_task(Request $request)
    {
        if ($auth = PermissionService::authorize('task', 'del')) return $auth;

        Task::findOrFail($request->task_id)->delete();
    }
}

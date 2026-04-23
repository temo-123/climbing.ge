<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User\Task;

class TaskController extends Controller
{
    public function get_all_tasks(Request $request)
    {
        $query = Task::with(['from_user', 'for_user'])->latest();
        if ($request->global_category) {
            $query->where('global_category', $request->global_category);
        }
        return $query->get();
    }

    public function get_user_tasks(Request $request)
    {
        $user = Auth::user();
        $query = Task::with(['from_user', 'for_user'])
            ->where('for_user_id', $user->id)
            ->latest();
        if ($request->global_category) {
            $query->where('global_category', $request->global_category);
        }
        return $query->get();
    }

    public function get_task_data(Request $request)
    {
        return Task::with(['from_user', 'for_user'])
            ->where('id', $request->task_id)
            ->first();
    }
}

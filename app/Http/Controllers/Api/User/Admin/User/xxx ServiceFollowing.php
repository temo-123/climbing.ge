<?php

namespace App\Http\Controllers\Api\User\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceFollowing extends Controller
{
    public function del_follower(Request $request)
    {
        // Implement the logic to delete a follower
        // For example, assuming there's a model for followers
        // Follower::where('id', $request->id)->delete();
        // Return appropriate response
    }

    public function get_following_users_list(Request $request)
    {
        // Implement the logic to get following users list
        // For example, return a list of users
        // return User::where(...)->get();
    }
}

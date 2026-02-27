<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class TeamMemberController extends Controller
{    public function get_team_members()
    {
        return User::where('is_team_member', '!=', null)->get();
    }
}

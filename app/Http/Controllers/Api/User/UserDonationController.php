<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserDonationController extends Controller
{
    public function my_donations()
    {
        return auth()->user()
            ->donations()
            ->orderBy('created_at', 'desc')
            ->get();
    }
}

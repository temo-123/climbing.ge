<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SummitController extends Controller
{
    public function get_all_my_ascents() {
        $auth_user = auth()->user();
        $ascents = $auth_user->ascents()->with('summit', 'route')->get();
        return response()->json($ascents);
    }

    public function del_ascent($id) {
        $auth_user = auth()->user();
        $ascent = $auth_user->ascents()->find($id);
        if ($ascent) {
            $ascent->delete();
            return response()->json(['message' => 'Ascent deleted successfully']);
        }
        return response()->json(['message' => 'Ascent not found'], 404);
    }
}

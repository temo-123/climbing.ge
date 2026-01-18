<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
    /**
     * Get the authenticated user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function user(Request $request): JsonResponse
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated',
                'user' => null
            ], 401);
        }

        // Check if user is banned
        if ($user->isBanned()) {
            return response()->json([
                'alert' => [
                    'type' => 'error',
                    'title' => 'Account Banned',
                    'message' => 'Your account has been banned.',
                    'icon' => 'ban'
                ],
                'user' => null,
                'is_banned' => true,
                'message' => 'Your account has been banned.',
            ], 403);
        }

        return response()->json($user);
    }
}


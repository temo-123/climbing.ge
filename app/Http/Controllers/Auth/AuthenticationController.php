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
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user) {
            $user->tokens()->delete();
        }

        // Log out the web (session) guard — this removes the auth identity from
        // the session so any subdomain sharing the parent-domain session cookie
        // immediately loses authentication.
        try {
            \Illuminate\Support\Facades\Auth::guard('web')->logout();
        } catch (\Throwable) {}

        // Belt-and-suspenders: also flush + invalidate the session directly.
        if ($request->hasSession()) {
            $request->session()->flush();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return response()->json(['message' => 'Logged out']);
    }

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

        $allPermissions = $user->getAllPermissions()->map(fn($p) => [
            'action'  => $p->action,
            'subject' => $p->subject,
        ])->values();

        return response()->json(array_merge($user->toArray(), [
            'casl_permissions' => $allPermissions,
        ]));
    }
}


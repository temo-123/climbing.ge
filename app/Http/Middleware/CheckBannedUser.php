<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckBannedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Check if user is authenticated and has ban role
        if ($user && $user->isBanned()) {
            // Revoke all tokens for banned user
            $user->tokens()->delete();

            return response()->json([
                'alert' => [
                    'type' => 'error',
                    'title' => 'Account Banned',
                    'message' => 'Your account has been banned. You cannot access this resource.',
                    'icon' => 'ban'
                ],
                'is_banned' => true,
                'message' => 'Your account has been banned. You cannot access this resource.',
            ], 403);
        }

        return $next($request);
    }
}


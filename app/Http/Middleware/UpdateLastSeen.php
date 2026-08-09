<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Stamps users.last_seen_at for the authenticated user, throttled so we
 * don't write on every single API request. Backs the "recently active"
 * filter on the admin Users list.
 */
class UpdateLastSeen
{
    private const THROTTLE_MINUTES = 5;

    public function handle(Request $request, Closure $next)
    {
        $user = auth('sanctum')->user();

        if ($user && (!$user->last_seen_at || $user->last_seen_at->diffInMinutes(now()) >= self::THROTTLE_MINUTES)) {
            $user->timestamps = false;
            $user->last_seen_at = now();
            $user->save();
        }

        return $next($request);
    }
}

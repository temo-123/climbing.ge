<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // For API requests, return JSON response instead of redirect
        if ($request->expectsJson() || $request->is('api/*') || $request->is('login')) {
            return null;
        }
        
        // For web requests, redirect to login
        return url('/login');
    }
}

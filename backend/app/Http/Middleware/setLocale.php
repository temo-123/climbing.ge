<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class setLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $lengPrefix = ltrim($request->route()->getPrefix(), "/");
        if($lengPrefix){
            App::setLocale($lengPrefix);
        }
        return $next($request);
    }
}

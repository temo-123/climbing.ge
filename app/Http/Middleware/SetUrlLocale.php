<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

/**
 * Page requests (the SPA catch-alls in routes/web.php) have no route prefix,
 * so setLocale never ran for them and app()->getLocale() stayed 'en' on
 * /ka/... pages — <html lang>, og:locale and the WebSite JSON-LD said English.
 * The locale comes from the first URL segment, the same rule as
 * SeoService::locale() and the Vue routers.
 */
class SetUrlLocale
{
    public function handle($request, Closure $next)
    {
        if ($request->segment(1) === 'ka') {
            App::setLocale('ka');
        }
        return $next($request);
    }
}

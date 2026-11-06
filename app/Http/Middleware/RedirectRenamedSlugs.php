<?php

namespace App\Http\Middleware;

use App\Services\UrlRedirectService;
use Closure;
use Illuminate\Http\Request;

/**
 * /{segment}/{old_slug} (and /ka/{segment}/{old_slug}) → 301 to the current slug, keeping
 * the language prefix and the query string. Runs before the SPA catch-alls on every
 * subdomain (blog and guidebook share /news/…).
 */
class RedirectRenamedSlugs
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
            return $next($request);
        }

        // path() is URL-encoded; slugs are stored decoded
        $parts = array_map('rawurldecode', explode('/', trim($request->path(), '/')));
        $ka = ($parts[0] ?? '') === 'ka';
        if ($ka) {
            array_shift($parts);
        }

        if (count($parts) === 2 && ($new = UrlRedirectService::find($parts[0], $parts[1]))) {
            $url = $request->getSchemeAndHttpHost() . ($ka ? '/ka' : '') . '/' . $parts[0] . '/' . rawurlencode($new);
            $query = $request->getQueryString();
            return redirect()->to($url . ($query ? '?' . $query : ''), 301);
        }

        return $next($request);
    }
}

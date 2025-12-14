<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CacheHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $maxAge = 3600)
    {
        $response = $next($request);

        if ($request->method() === 'GET' && $response->status() === 200) {
            $response->header('Cache-Control', "public, max-age={$maxAge}");
            $response->header('Expires', gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT');
        }

        return $response;
    }
}

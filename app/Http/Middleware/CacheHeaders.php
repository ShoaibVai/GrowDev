<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CacheHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $maxAge = 3600)
    {
        $response = $next($request);

        // Skip cache headers for streamed responses (like file downloads)
        if ($response instanceof StreamedResponse) {
            return $response;
        }

        // NEVER cache authenticated user pages to prevent showing cached data from other users
        if (Auth::check()) {
            $response->header('Cache-Control', 'no-cache, no-store, must-revalidate, private');
            $response->header('Pragma', 'no-cache');
            $response->header('Expires', '0');
            return $response;
        }

        // Only cache public GET requests with successful responses
        if ($request->method() === 'GET' && $response->getStatusCode() === 200) {
            $response->header('Cache-Control', "public, max-age={$maxAge}");
            $response->header('Expires', gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT');
        }

        return $response;
    }
}

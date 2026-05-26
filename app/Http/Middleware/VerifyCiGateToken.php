<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyCiGateToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = config('tasks.ci_gate_token');
        $provided = (string) $request->bearerToken();

        abort_if(blank($expected) || !hash_equals((string) $expected, $provided), 403);

        return $next($request);
    }
}

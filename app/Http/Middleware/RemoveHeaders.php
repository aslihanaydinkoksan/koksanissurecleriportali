<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RemoveHeaders
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
        $response = $next($request);

        $response->headers->remove('X-Powered-By');

        $response->headers->remove('Server');

        $response->headers->remove('X-RateLimit-Limit');
        $response->headers->remove('X-RateLimit-Remaining');
        $response->headers->remove('X-RateLimit-Reset');
        $response->headers->remove('Retry-After');

        return $response;
    }
}

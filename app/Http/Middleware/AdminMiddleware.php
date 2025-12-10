<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Eğer giriş yapmamışsa veya rolü admin değilse
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            // Ana sayfaya at ve hata göster
            return redirect('/')->with('error', 'Bu sayfaya erişim yetkiniz yok.');
        }

        return $next($request);
    }
}

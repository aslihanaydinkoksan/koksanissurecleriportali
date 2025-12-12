<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetDefaultBusinessUnit
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Kullanıcı giriş yapmışsa VE session'da birim seçili değilse
        if ($user && !session()->has('active_unit_id')) {

            // Kullanıcının yetkili olduğu ilk birimi al
            // (User modelinde 'businessUnits' ilişkisi tanımlı olmalı)
            $firstUnit = $user->businessUnits->first();

            if ($firstUnit) {
                session(['active_unit_id' => $firstUnit->id]);
                session(['active_unit_name' => $firstUnit->name]);
            }
        }

        return $next($request);
    }
}
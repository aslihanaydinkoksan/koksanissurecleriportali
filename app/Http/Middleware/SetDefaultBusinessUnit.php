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

        if ($user && !session()->has('active_unit_id')) {
            $authorizedUnits = $user->getAuthorizedBusinessUnits();
            $firstUnit = $authorizedUnits->first();

            if ($firstUnit) {
                session(['active_unit_id' => $firstUnit->id]);
                session(['active_unit_name' => $firstUnit->name]);
            }
        }

        return $next($request);
    }
}
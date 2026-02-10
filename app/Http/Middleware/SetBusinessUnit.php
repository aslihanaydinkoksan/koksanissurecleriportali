<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SetBusinessUnit
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            // 1. Session'da seçili bir birim var mı?
            $activeUnitId = Session::get('active_unit_id');

            // 2. Kullanıcının yetkili olduğu birimleri al
            $userUnits = Auth::user()->businessUnits;

            // 3. Eğer session boşsa veya kullanıcı o birimden atıldıysa, ilk yetkili olduğu birimi seç
            if (!$activeUnitId || !$userUnits->contains('id', $activeUnitId)) {
                $firstUnit = $userUnits->first();
                if ($firstUnit) {
                    Session::put('active_unit_id', $firstUnit->id);
                    Session::put('active_unit_name', $firstUnit->name); // Ekranda göstermek için
                }
            }
        }

        return $next($request);
    }
}
<?php

namespace App\Observers;

use Illuminate\Support\Facades\Auth;

class BusinessUnitObserver
{
    public function creating($model)
    {
        // Eğer kullanıcı giriş yapmışsa ve modelde business_unit_id henüz atanmamışsa
        if (Auth::check() && empty($model->business_unit_id)) {

            // 1. Önce Session'a bak (Middleware'in ayarladığı)
            $unitId = session('active_unit_id');

            // 2. Session yoksa kullanıcının ilk yetkili birimini al
            if (!$unitId && Auth::user()->businessUnits->count() > 0) {
                $unitId = Auth::user()->businessUnits->first()->id;
            }

            // 3. Bulduğun ID'yi modele yaz
            if ($unitId) {
                $model->business_unit_id = $unitId;
            }
        }
    }
}
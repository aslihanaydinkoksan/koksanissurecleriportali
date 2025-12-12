<?php

namespace App\Traits;

use App\Models\BusinessUnit;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait HasBusinessUnit
{
    public function businessUnit()
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    public function scopeForUser(Builder $query, User $user)
    {
        // 1. ADIM: Admin ise her şeyi görsün
        if ($user->hasRole('admin')) {
            return $query;
        }

        // 2. ADIM: Modele Özel Global Yetki Kontrolü (NOKTA ATIŞI) 🎯
        // Modelde 'globalPermission' diye bir değişken tanımlı mı?
        if (isset(static::$globalPermission)) {
            // Kullanıcıda bu özel yetki var mı? (Örn: 'manage_fleet')
            if ($user->can(static::$globalPermission)) {
                return $query; // Varsa filtreleme yapma, hepsini göster!
            }
        }

        // 3. ADIM: Standart Birim Filtrelemesi (Lokal Erişim)
        $activeUnitId = session('active_unit_id');

        if ($activeUnitId) {
            return $query->where('business_unit_id', $activeUnitId);
        }

        // Session yoksa kullanıcının yetkili olduğu birimleri getir
        $authorizedUnitIds = $user->businessUnits->pluck('id');

        if ($authorizedUnitIds->isEmpty()) {
            return $query->whereNull('id'); // Hiçbir şey gösterme
        }

        return $query->whereIn('business_unit_id', $authorizedUnitIds);
    }
}
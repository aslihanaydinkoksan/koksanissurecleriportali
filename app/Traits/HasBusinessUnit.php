<?php

namespace App\Traits;

use App\Models\BusinessUnit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

trait HasBusinessUnit
{
    /**
     * Model "boot" edildiğinde otomatik çalışacak metod.
     * İsim standardı: boot[TraitAdi]
     */
    public static function bootHasBusinessUnit()
    {
        // 1. OTOMATİK FİLTRELEME (READ)
        static::addGlobalScope('business_unit_scope', function (Builder $builder) {

            // A) ÖNCE SESSION KONTROLÜ (En Yüksek Öncelik)
            // Admin bile olsa, eğer yukarıdan "Levha" fabrikası seçildiyse sadece onu görmeli.
            if (Session::has('active_unit_id')) {
                $builder->where('business_unit_id', Session::get('active_unit_id'));
                return; // Filtre uygulandı, işlem tamam.
            }

            // B) SESSION YOKSA -> ROL KONTROLÜ
            $user = Auth::user();
            if (!$user)
                return;

            // Admin ise ve birim seçmediyse her şeyi görsün
            if ($user->hasRole(['admin', 'super-admin'])) {
                return;
            }

            // C) ÖZEL YETKİ KONTROLÜ (Modele has yetki varsa)
            // Örn: Event modelinde 'manage_all_events' yetkisi tanımlıysa
            if (isset(static::$globalPermission) && $user->can(static::$globalPermission)) {
                return;
            }

            // D) STANDART KULLANICI KISITLAMASI
            // Hiçbir seçim yoksa sadece yetkili olduğu birimleri görsün
            $authorizedUnitIds = $user->businessUnits->pluck('id');

            if ($authorizedUnitIds->isEmpty()) {
                // Yetkisi yoksa, imkansız bir sorgu (0=1) ile boş liste döndür
                $builder->whereRaw('1 = 0');
            } else {
                $builder->whereIn('business_unit_id', $authorizedUnitIds);
            }
        });

        // 2. OTOMATİK KAYIT (CREATE)
        // Veri eklerken seçili birimi otomatik kaydet
        static::creating(function (Model $model) {
            if (Session::has('active_unit_id')) {
                $model->business_unit_id = Session::get('active_unit_id');
            }
        });
    }

    /**
     * İlişki Tanımı
     */
    public function businessUnit()
    {
        return $this->belongsTo(BusinessUnit::class);
    }
}
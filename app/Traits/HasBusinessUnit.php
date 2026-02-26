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
     */
    public static function bootHasBusinessUnit()
    {
        static::addGlobalScope('business_unit_scope', function (Builder $builder) {

            // 🛑 1. GÜVENLİK FRENİ: SONSUZ DÖNGÜYÜ ENGELLER
            // User modeli kendi içinde yetki kontrolü yaparken tekrar kendini çağırmasın.
            if ($builder->getModel() instanceof \App\Models\User) {
                return;
            }

            // A) SESSION KONTROLÜ (Levha Fabrikası vb. seçildiyse)
            if (Session::has('active_unit_id')) {
                $builder->where('business_unit_id', Session::get('active_unit_id'));
                return;
            }

            // B) KULLANICI KONTROLÜ
            $userId = Auth::id();
            if (!$userId) return;

            $user = Auth::user();

            // Admin ise her şeyi görsün
            if ($user->hasRole(['admin', 'super-admin'])) {
                return;
            }

            // C) ÖZEL YETKİ KONTROLÜ (HATA BURADAYDI, DÜZELTİLDİ) ✅
            // Statik değişkene doğrudan erişmek yerine, sınıf üzerinden güvenli erişim yapıyoruz.
            $className = static::class;

            if (property_exists($className, 'globalPermission')) {
                // Değişken varsa değerini al (Örn: 'view_all_events')
                $permission = $className::$globalPermission;

                if ($user->can($permission)) {
                    return;
                }
            }

            // D) STANDART KISITLAMA
            // Kullanıcı sadece yetkili olduğu birimleri görsün
            // User modelini yukarıda (1. Fren) hariç tuttuğumuz için burası artık güvenli.
            $authorizedUnitIds = $user->businessUnits->pluck('id');

            if ($authorizedUnitIds->isEmpty()) {
                // Yetkisi yoksa, boş liste döndür (0=1)
                $builder->whereRaw('1 = 0');
            } else {
                $builder->whereIn('business_unit_id', $authorizedUnitIds);
            }
        });

        // 2. OTOMATİK KAYIT (CREATE)
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

class Location extends Model
{
    use SoftDeletes, Loggable;
    protected $guarded = [];

    // --- İlişkiler ---

    // Kendisi bir alt birimse, üst birimi kim? (Daire -> Blok)
    public function parent()
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }

    // Kendisine bağlı alt birimler (Blok -> Daireler)
    public function children()
    {
        return $this->hasMany(Location::class, 'parent_id');
    }

    // Bu mekana atanmış demirbaşlar
    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    // Bu mekana ait abonelikler
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    // Bu mekana atanmış ustalar (Doğrudan atama)
    public function serviceAssignments()
    {
        return $this->hasMany(ServiceAssignment::class);
    }

    // Şu an burada kalanlar (Konaklamalar)
    public function currentStays()
    {
        return $this->hasMany(Stay::class)->whereNull('check_out_date');
    }

    // --- AKILLI MANTIK (BUSINESS LOGIC) ---

    /**
     * İstenilen hizmet türü için sorumlu kişiyi bulur.
     * Eğer bu odada tanımlı değilse, binaya bakar, orada yoksa siteye bakar.
     * Örn: $location->getResponsibleContact('electric');
     * getResponsibleContact fonksiyonu sayesinde, sadece Site Yönetimi lokasyonuna 
     * "Ahmet Usta"yı ekleyeceksin, altındaki 500 odanın hepsi otomatik olarak 
     * Ahmet Usta'yı görecek.
     */
    public function getResponsibleContact($serviceType)
    {
        // 1. Önce bu lokasyona (örn: Oda 101) özel bir atama var mı?
        $assignment = $this->serviceAssignments()
            ->where('service_type', $serviceType)
            ->with('contact') // N+1 sorununu önlemek için
            ->first();

        if ($assignment) {
            return $assignment->contact;
        }

        // 2. Yoksa ve bu yerin bir üstü varsa (Daire -> Bina), oraya sor (Recursive)
        if ($this->parent) {
            return $this->parent->getResponsibleContact($serviceType);
        }

        // 3. Hiçbir yerde yoksa null dön
        return null;
    }
    /**
     * Bu mekanın altına hangi türde alt birimler eklenebilir?
     * @return array ['value' => 'Görünecek İsim']
     */
    public function getAllowedChildTypes()
    {
        return match ($this->type) {
            // SİTE / BÖLGE içindeysek:
            // Hem BLOK eklemeye izin ver, Hem de direkt DAİRE eklemeye izin ver.
            'site', 'campus' => [
                'block' => 'Blok (Apartman Bloğu)', // Örn: C Blok
                'apartment' => 'Bağımsız Daire / Konut / Villa', // Örn: Şimfa Garden 27
                'common_area' => 'Ortak Alan (Park, Havuz vb.)',
            ],

            // BLOK içindeysek:
            // Sadece Daire ve Depo eklenebilir. (Blok içine blok eklenemez)
            'block' => [
                'apartment' => 'Daire (Apartment)',
                'common_area' => 'Blok İçi Depo / Sığınak',
            ],

            // DAİRE / KONUT içindeysek:
            // Sadece Oda eklenebilir.
            'apartment' => [
                'room' => 'Oda',
            ],

            // Bunların altına bir şey eklenemez
            'room', 'common_area' => [],

            default => [],
        };
    }

    /**
     * En üst seviye (Parent yoksa) eklenebilecekler
     */
    public static function getRootTypes()
    {
        return [
            'site' => 'Site / Lojman Grubu',
            'campus' => 'Kampüs / Yerleşke',
        ];
    }
}

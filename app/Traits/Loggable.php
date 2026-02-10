<?php

namespace App\Traits;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Str;

trait Loggable
{
    // Spatie'nin ana Trait'ini buraya dahil ediyoruz
    use LogsActivity;

    // --- BÖLÜM 1: v3/v4 STİLİ LOG ADI AYARLAMASI ---

    /**
     * Log adını tutacak statik özellik (v4.7.3'te Gerekli)
     */
    protected static $logName = 'default';

    /**
     * Bu 'boot' metodu, log adını model adından otomatik olarak atar.
     */
    public static function bootLoggable()
    {
        // Model adını al (Örn: "CustomerVisit" -> "Customer Visit")
        static::$logName = Str::of(class_basename(new static()))->snake(' ')->title();
    }

    // --- BÖLÜM 2: v4/v5 STİLİ DİĞER AYARLAR ---

    /**
     * Bu metod, v4.7.3'te ZORUNLUDUR.
     * Logun 'içeriğini' ayarlar.
     */
    public function getActivitylogOptions(): LogOptions
    {
        // Model adını al (Örn: "CustomerVisit" -> "customer visit")
        $modelNameLower = Str::of(class_basename($this))->snake(' ')->lower();

        return LogOptions::defaults()

            // DİKKAT: ->logName() METODU BURADAN KALDIRILDI! Hata buydu.

            // Tüm alanları logla
            ->logAll()

            // Bu alanlar değişirse log atma (gereksiz kalabalık yapmasın)
            ->dontLogIfAttributesChangedOnly([
                'remember_token',
                'created_at',
                'updated_at',
                'deleted_at'
            ])

            // 'password' alanını gizle
            ->logOnlyDirty()

            // Değişiklik yoksa boş log atma
            ->dontSubmitEmptyLogs()

            // Log mesajını ayarla
            ->setDescriptionForEvent(function (string $eventName) use ($modelNameLower) {
                if ($eventName === 'created') {
                    return "yeni bir {$modelNameLower} kaydı oluşturdu.";
                }
                if ($eventName === 'updated') {
                    return "bir {$modelNameLower} kaydını güncelledi.";
                }
                if ($eventName === 'deleted') {
                    return "bir {$modelNameLower} kaydını sildi.";
                }
                return "bir {$modelNameLower} kaydı ile {$eventName} işlemi yaptı.";
            });
    }
}

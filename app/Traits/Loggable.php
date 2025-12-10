<?php

namespace App\Traits;

use App\Models\SystemLog;
use Illuminate\Support\Facades\Auth;

trait Loggable
{
    public static function bootLoggable()
    {
        // Kayıt oluşturulduğunda
        static::created(function ($model) {
            self::logToDb($model, 'create');
        });

        // Kayıt güncellendiğinde
        static::updated(function ($model) {
            self::logToDb($model, 'update', $model->getOriginal(), $model->getChanges());
        });

        // Kayıt silindiğinde (Soft Delete)
        static::deleted(function ($model) {
            self::logToDb($model, 'delete');
        });
    }

    protected static function logToDb($model, $action, $old = null, $new = null)
    {
        // Log tablosuna kaydet
        SystemLog::create([
            'user_id' => Auth::id(), // Giriş yapan kullanıcı
            'action' => $action,
            'loggable_id' => $model->id,
            'loggable_type' => get_class($model),
            'old_values' => $old ? json_encode($old) : null,
            'new_values' => $new ? json_encode($new) : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
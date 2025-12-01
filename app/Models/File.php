<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $guarded = [];

    // Dosyanın sahibi olan model (Araç görevi, Seyahat vb.)
    public function fileable()
    {
        return $this->morphTo();
    }

    // Dosyayı yükleyen kişi
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Dosya boyutunu (KB/MB) okunaklı hale getiren yardımcı fonksiyon (Opsiyonel)
    public function getSizeAttribute()
    {
        // Dosya boyutu işlemleri eklenebilir
        return 'Unknown';
    }
}
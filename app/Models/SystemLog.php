<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    use HasFactory;

    // Veritabanındaki tablo adımız (Migration ile aynı olmalı)
    protected $table = 'system_logs';

    // Mass assignment korumasını kaldırıyoruz
    protected $guarded = [];

    // JSON verileri otomatik diziye (array) çevir
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    // İlişkiler: Log kime ait? (Polymorphic)
    // Migration'da "loggable" dediğimiz için burası da "loggable" olmalı.
    public function loggable()
    {
        return $this->morphTo();
    }

    // İlişkiler: İşlemi kim yaptı?
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
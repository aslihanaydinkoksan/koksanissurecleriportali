<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_name',
        'report_class',
        'frequency',
        'filter_frequency',
        'send_time',
        'recipients',
        'file_format',
        'is_active',
        'last_sent_at'
    ];

    protected $casts = [
        'recipients' => 'array',
        'last_sent_at' => 'datetime',
    ];

    /**
     * Ön Tanımlı Zamanlama Seçenekleri (Presets)
     */
    public static function getTimingPresets(): array
    {
        return [
            'minute' => ['label' => '⚠️ Test: Her Dakika', 'frequency' => 'minute', 'time' => '00:00'],
            'morning' => ['label' => 'Her Sabah (09:00)', 'frequency' => 'daily', 'time' => '09:00'],
            'evening' => ['label' => 'Her Akşam (18:30)', 'frequency' => 'daily', 'time' => '18:30'],
            'weekly' => ['label' => 'Pazartesi Sabahı', 'frequency' => 'weekly', 'time' => '09:00'],
            'monthly' => ['label' => 'Ayın İlk Günü', 'frequency' => 'monthly', 'time' => '09:00'],
        ];
    }
}
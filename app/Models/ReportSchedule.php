<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

class ReportSchedule extends Model
{
    // Senin projendeki log tutma ve silineni saklama özelliklerini ekledik
    use SoftDeletes, Loggable;

    protected $fillable = [
        'report_name',
        'report_type',
        'frequency',
        'data_scope',
        'file_format',
        'recipients',
        'is_active'
    ];

    /**
     * recipients alanını veritabanında JSON tutuyoruz, 
     * kod içinde otomatik diziye (array) çevrilmesini sağlıyoruz.
     */
    protected $casts = [
        'recipients' => 'array',
        'last_sent_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}
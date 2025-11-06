<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * Toplu atanabilir alanlar.
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'start_datetime',
        'end_datetime',
        'location',
        'event_type',
        'is_important',
    ];

    /**
     * Tarih alanlarını otomatik olarak Carbon nesnesine dönüştürür.
     * Bu, takvimde ve formlarda gösterim için çok önemlidir.
     */
    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'is_important' => 'boolean',
    ];

    /**
     * Etkinliği oluşturan kullanıcı.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

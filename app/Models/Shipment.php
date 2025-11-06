<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'shipment_type',
        'arac_tipi',
        'plaka',
        'dorse_plakasi',
        'sofor_adi',
        'imo_numarasi',
        'gemi_adi',
        'kalkis_limani',
        'varis_limani',
        'kalkis_noktasi',
        'varis_noktasi',
        'kargo_icerigi',
        'kargo_tipi',
        'kargo_miktari',
        'cikis_tarihi',
        'tahmini_varis_tarihi',
        'onaylanma_tarihi',
        'onaylayan_user_id',
        'ekstra_bilgiler', // Bu alanı artık kullanmayabiliriz veya başka amaçlar için tutabiliriz
        'aciklamalar',
        'dosya_yolu',
        'is_important',
    ];
    protected $casts = [
        'cikis_tarihi' => 'datetime',
        'tahmini_varis_tarihi' => 'datetime',
        'onaylanma_tarihi' => 'datetime',
        'is_important' => 'boolean',
    ];

    public function onaylayanKullanici(): BelongsTo
    {
        return $this->belongsTo(User::class, 'onaylayan_user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

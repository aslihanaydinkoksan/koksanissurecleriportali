<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomFieldDefinition extends Model
{
    use HasFactory;
    protected $fillable = [
        'model_scope',
        'business_unit_id',
        'key',
        'label',
        'type',
        'options',
        'is_required',
        'is_active',
        'order'
    ];

    // JSON veriyi otomatik array'e çevirmek için
    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    /**
     * Trait içindeki scope metodu
     * Belirli bir modelin (Örn: Shipment) aktif alanlarını getirir.
     */
    public static function getCustomFieldsFor($modelClass)
    {
        return self::where('model_scope', $modelClass)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }
    public function businessUnit()
    {
        return $this->belongsTo(BusinessUnit::class);
    }
}
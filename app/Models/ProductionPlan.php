<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionPlan extends Model
{
    use HasFactory;
    /**
     * Toplu atanabilir alanlar.
     */
    protected $fillable = [
        'user_id',
        'plan_title',
        'week_start_date',
        'plan_details',
    ];

    /**
     * JSON alanını otomatik olarak array'e dönüştürmek için.
     */
    protected $casts = [
        'week_start_date' => 'date',
        'plan_details' => 'array',
    ];

    /**
     * Planı oluşturan kullanıcı.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

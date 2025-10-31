<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleAssignment extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'vehicle_id',
        'user_id',
        'task_description',
        'destination',
        'requester_name',
        'start_time',
        'end_time',
        'notes',
    ];

    // Tarih alanlarını Carbon nesnesi yap
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Bu atamanın hangi araca ait olduğunu belirtir
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    // Bu atamayı hangi kullanıcının yaptığını belirtir
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

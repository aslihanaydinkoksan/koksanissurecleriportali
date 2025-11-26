<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintenanceTimeEntry extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $casts = ['start_time' => 'datetime', 'end_time' => 'datetime'];

    public function plan()
    {
        return $this->belongsTo(MaintenancePlan::class, 'maintenance_plan_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

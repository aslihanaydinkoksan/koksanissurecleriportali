<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleAssignmentHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_assignment_id',
        'user_id',
        'old_status',
        'new_status',
        'note'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignment()
    {
        return $this->belongsTo(VehicleAssignment::class, 'vehicle_assignment_id');
    }
}

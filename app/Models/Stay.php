<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

class Stay extends Model
{
    use Loggable, SoftDeletes;
    protected $guarded = [];

    // JSON verileri diziye çevir
    protected $casts = [
        'check_in_items' => 'array',
        'check_out_items' => 'array',
        'check_in_date' => 'datetime',
        'check_out_date' => 'datetime',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}

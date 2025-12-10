<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

class Asset extends Model
{
    use HasFactory, SoftDeletes, Loggable;

    protected $guarded = []; // Tüm alanlara izin ver

    // İlişki: Bu eşya hangi mekanda?
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
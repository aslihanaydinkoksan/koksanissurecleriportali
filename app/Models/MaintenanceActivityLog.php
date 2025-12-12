<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasBusinessUnit; // <--- 1. Use ekle

class MaintenanceActivityLog extends Model
{
    use HasFactory, HasBusinessUnit; // <--- 2. Trait ekle
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

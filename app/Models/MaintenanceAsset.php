<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasBusinessUnit; // <--- 1. Use ekle

class MaintenanceAsset extends Model
{
    use HasFactory, SoftDeletes, HasBusinessUnit; // <--- 2. Trait ekle
    protected $guarded = [];
    public function plans()
    {
        return $this->hasMany(MaintenancePlan::class);
    }
    // Dosyalar İlişkisi
    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}

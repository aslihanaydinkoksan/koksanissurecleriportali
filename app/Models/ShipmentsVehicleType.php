<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipmentsVehicleType extends Model
{
    use HasFactory, SoftDeletes; // <--- 2. Trait ekle
    public static $globalPermission = 'manage_fleet';
    protected $fillable = ['name'];
}

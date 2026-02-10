<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessUnit extends Model
{
    use HasFactory, SoftDeletes;

    // Mass assignment koruması için
    protected $fillable = ['name', 'code', 'slug', 'is_active'];

    // İlişki: Bir birimde çalışanlar
    public function users()
    {
        return $this->belongsToMany(User::class, 'business_unit_user')
            ->withPivot('role_in_unit')
            ->withTimestamps();
    }
}
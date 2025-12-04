<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['key', 'name'];

    // Bir yetki birden fazla role ait olabilir
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role');
    }
}
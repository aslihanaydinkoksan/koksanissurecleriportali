<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Birim extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Toplu atama (mass assignment) ile doldurulmasına izin verilen alanlar.
     *
     * @var array
     */
    protected $fillable = [
        'ad',
    ];
}

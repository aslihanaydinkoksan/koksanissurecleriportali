<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Birim extends Model
{
    use HasFactory;

    /**
     * Toplu atama (mass assignment) ile doldurulmasına izin verilen alanlar.
     *
     * @var array
     */
    protected $fillable = [
        'ad',
    ];
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Travel extends Model
{
    use HasFactory;
    protected $table = 'travels';
    protected $fillable = [
        'user_id',
        'name',
        'start_date',
        'end_date',
        'status',
        'is_important',
    ];
    /**
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function customerVisits()
    {
        return $this->hasMany(CustomerVisit::class);
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class)->orderBy('start_datetime', 'asc');
    }
}

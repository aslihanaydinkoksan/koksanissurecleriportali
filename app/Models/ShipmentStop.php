<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasBusinessUnit;

class ShipmentStop extends Model
{
    use HasFactory, HasBusinessUnit;

    protected $table = 'shipment_stops';

    protected $fillable = [
        'shipment_id',
        'location_name',
        'dropped_amount',
        'remaining_amount',
        'stop_date',
        'receiver_name',
        'note',
        'business_unit_id',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }
}

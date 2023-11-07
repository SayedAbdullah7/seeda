<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceConfig extends Model
{
    use HasFactory,ActivityLogTraits;

    protected $fillable=[
        "shipment_type_id",
        "ride_types_id",
        "km_price",
        "waiting_time_price",
        "traffic_jam_price",
        "move_minute_price",
        "appKey",
    ];

    public function RideTypes(){
        return $this->belongsTo(RideTypes::class);
    }
}

<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicles extends Model
{
    use HasFactory , ActivityLogTraits;

    protected $fillable=[
        "vehicle_types_id",
        "Vehicle_color_id",
        "purchase_year",
        "car_number",
        "user_id",
        "admin_id",
        "owner_id",
        "is_approve",
        "is_active",
        "ride_types_id",
        "appKey",
        "vehicles_color",
        "is_open",
        'in_zone',
        'is_closed',
        'mileage',
        'order_ended',
    ];

    public function medias(){
        return $this->morphMany("App\Models\Medias","mediaable");
    }

    public function VehicleTypes(){
        return $this->belongsTo(VehicleTypes::class);
    }

    public function VehicleColor(){
        return $this->belongsTo(VehicleColor::class,"Vehicle_color_id");
    }
    public function rideType(){
        return $this->belongsTo(RideTypes::class,"ride_types_id");
    }

    public function locations(){
        return $this->morphOne(Location::class, 'locationable');
    }

    public function Order(){
        return $this->hasMany(Order::class,"scooter_id")->where("status","end");
    }
}

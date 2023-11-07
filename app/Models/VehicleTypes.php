<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleTypes extends Model
{
    use HasFactory;

    protected $fillable=[
        "company",
        "type",
        "model",
        "passengers",
        "ride_types_id",
    ];

    public function medias(){
        return $this->morphOne("App\Models\Medias","mediaable");
    }

    public function rideType(){
        return $this->belongsTo(RideTypes::class,"ride_types_id","id");
    }
}

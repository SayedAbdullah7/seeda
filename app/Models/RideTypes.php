<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RideTypes extends Model
{
    use HasFactory ,ActivityLogTraits;

    protected $fillable=[
        "name",
        "is_active",
    ];

    public function Vehicle(){
        return $this->belongsTo(Vehicles::class);
    }

    public function Price(){
        return $this->hasOne(PriceConfig::class,"ride_types_id")->where("appKey",appKey())->where("shipment_type_id",request()->get("shipmentType"));
    }

    public function Medias(){
        return $this->morphOne(Medias::class,"mediaable");
    }
}

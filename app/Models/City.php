<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory,ActivityLogTraits;
    protected $guarded=[];

    public function country(){
        return $this->belongsTo(Countries::class,"country_id","id");
    }

    public function Geofence(){
        return $this->morphMany(Geofence::class,"geofenceable");
    }
}

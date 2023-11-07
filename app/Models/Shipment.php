<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory,ActivityLogTraits;
    public function currentLocation()
    {
        return $this->morphOne(Location::class, 'locationable');
    }
}

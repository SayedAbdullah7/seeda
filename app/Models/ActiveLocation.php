<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveLocation extends Model
{
    use HasFactory,ActivityLogTraits;

    public $fillable=[
        "locationable_type",
        "locationable_id",
        "latitude",
        "longitude",
        "address",
        "type",
    ];

    public function locationable()
    {
        return $this->morphTo();
    }
}

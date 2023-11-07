<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleLogs extends Model
{
    use HasFactory,ActivityLogTraits;

    protected $fillable=[
        "user_id",
        "vehicles_id",
        "admin_id",
        "day",
    ];
}

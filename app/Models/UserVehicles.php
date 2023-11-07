<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVehicles extends Model
{
    use HasFactory , ActivityLogTraits;

    protected $fillable=[
        "user_id",
        "vehicles_id",
    ];

    public function Vehicles(){
        return $this->belongsTo(Vehicles::class);
    }

    public function User(){
        return $this->belongsTo(User::class);
    }
}

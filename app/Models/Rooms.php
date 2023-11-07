<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{
    use HasFactory,ActivityLogTraits;

    protected $table = "rooms";

    protected $fillable=["type","order_id","appKey","task_id"];

    public function UserRooms(){
        return $this->hasMany(UserRooms::class,"room_id","id");
    }

    public function Massages(){
        return $this->hasMany(Massages::class,"room_id","id");
    }
}

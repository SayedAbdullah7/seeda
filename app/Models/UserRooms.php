<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRooms extends Model
{
    use HasFactory,ActivityLogTraits;
    protected $table ="users_in_rooms";

    protected $fillable = [
        "user_id",
        "room_id",
    ];

    public function Room(){
        return $this->belongsTo(Rooms::class);
    }

    public function User(){
        return $this->belongsTo(User::class);
    }

}

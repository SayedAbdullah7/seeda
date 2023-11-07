<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Massages extends Model
{
    use HasFactory,ActivityLogTraits;

    protected $table="messages";

    protected $fillable=[
        "user_id",
        "to_user_id",
        "room_id",
        "message",
        "media_id",
    ];

    public function Rooms(){
        return $this->belongsTo(Rooms::class);
    }

    public function User(){
        return $this->belongsTo(User::class,"user_id","id");
    }

    public function medias(){
        return $this->morphOne("App\Models\Medias","mediaable");
    }

}

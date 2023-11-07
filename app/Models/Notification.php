<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory,ActivityLogTraits;
    public $tranlations=['title','content'];
    public $guarded=[];

    public function Notify(){
        return $this->hasMany(Notify::class);
    }

    protected $casts = [
        "content"=>"array"
    ];

}

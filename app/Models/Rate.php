<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory,ActivityLogTraits;
    public $guarded=[];
    public function rateable()
    {
        return $this->morphTo();
    }

    public function User(){
        return $this->belongsTo(User::class,"user_id","id");
    }

    public $casts=[
        'rate'=>'float',
    ];
}

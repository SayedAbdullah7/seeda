<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory,ActivityLogTraits;

    protected $guarded=[];

    public function Subscription(){
        return $this->belongsTo(Subscription::class,"subscriptions_id");
    }
}

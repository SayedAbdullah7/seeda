<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\orderStatus;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory,ActivityLogTraits,SoftDeletes;
    public $guarded=[];

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function driver()
    {
        return $this->hasOne(OrderSentToDriver::class)->whereIn('status',[
            orderStatus::accept,
            orderStatus::start,
            orderStatus::arrived,
            orderStatus::end,
        ]);
    }

    public function OrderDetails(){
        return $this->hasOne(OrderDetails::class);
    }
    public function locations()
    {
        return $this->morphMany(Location::class, 'locationable');
    }

    public function Rate()
    {
        return $this->morphOne(Rate::class, 'rateable');
    }

    public function medias(){
        return $this->morphMany("App\Models\Medias","mediaable");
    }

    public function Card(){
        return $this->belongsTo(Card::class,"card_id");
    }

    public function orderPassenger(){
        return $this->hasMany(orderPassenger::class);
    }

    public function Vehicles(){
       return $this->belongsTo(Vehicles::class,"scooter_id");
    }
}

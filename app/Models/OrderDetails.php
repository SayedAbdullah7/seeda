<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory , ActivityLogTraits;

    protected $fillable=[
        "order_id",
        "start_time",
        "end_time",
        "time_taken",
        "distance",
        "price",
        "discount",
        "accept_time",
        "waiting_time",
        "order_details",
        "captainTax",
        "userTax",
        "captainPrice",
        "userPrice",
        "long_line",
        "lat_line",
        "arrived_time",
    ];

    protected $casts=[
        "order_details"=>"array",
        "long_line"=>"array",
        "lat_line"=>"array"
    ];

    public function Order(){
        return $this->belongsTo(Order::class);
    }
}

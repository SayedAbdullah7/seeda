<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderSentToDriver extends Model
{
    use HasFactory,ActivityLogTraits;
    public $table="order_sent_to_drivers";
    protected $guarded =[];
    function user()
    {
        return $this->belongsTo(User::class);
    }
}

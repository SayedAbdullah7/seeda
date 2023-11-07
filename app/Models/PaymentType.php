<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
    use HasFactory,ActivityLogTraits;
    protected $fillable=[
        "key",
        "link",
        "appKey",
    ];
}

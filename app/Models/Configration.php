<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configration extends Model
{
    use HasFactory,ActivityLogTraits;

    protected $fillable=[
        "key",
        "value",
        "appKey",
    ];

    protected $casts=[
        "value"=>"array"
    ];
}

<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shipmentType extends Model
{
    use HasFactory,ActivityLogTraits;
    public $table="shipment_type";
}

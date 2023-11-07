<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory,ActivityLogTraits;
    public $guarded=[];
    public function locationable()
    {
        return $this->morphTo();
    }
}

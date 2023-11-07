<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stations extends Model
{
    use HasFactory;

    protected $guarded=[];
    public function Location()
    {
        return $this->morphOne(Location::class, 'locationable');
    }

}

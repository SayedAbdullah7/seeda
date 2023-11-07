<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Points extends Model
{
    use HasFactory,ActivityLogTraits;
    protected $fillable=[
        "user_id",
        "points",
    ];

    public function Transaction(){
        return $this->morphMany(Transaction::class,"transable");
    }
}

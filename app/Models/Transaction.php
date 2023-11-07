<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory,ActivityLogTraits;
    protected $table="transactions";

    protected $fillable = [
        "transable_id",
        "transable_type",
        "amount",
        "type",
    ];

    public function transable(){
        return $this->morphTo();
    }
}

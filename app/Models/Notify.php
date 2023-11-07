<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notify extends Model
{
    public $table='notify';
    use HasFactory,ActivityLogTraits;

    protected $guarded=[];

    public function notification(){
        return $this->belongsTo(Notification::class);
    }

}

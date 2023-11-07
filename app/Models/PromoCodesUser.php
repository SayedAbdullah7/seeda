<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCodesUser extends Model
{
    use HasFactory,ActivityLogTraits;
    protected $fillable=[
        "user_id",
        "promo_codes_id",
        "num_of_apply",
        "status",
    ];

    public function PromoCode(){
        return $this->belongsTo(PromoCode::class,"promo_codes_id");
    }
}

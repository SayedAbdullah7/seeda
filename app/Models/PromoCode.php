<?php

namespace App\Models;

use App\Traits\ActivityLogTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory,ActivityLogTraits;
    protected $fillable=[
        "promo_type",
        "title",
        "code",
        "type",
        "discount",
        "num_of_use",
        "min_amount",
        "max_amount",
        "status",
        "applied_type",
        "applied_id",
        "start_at",
        "expire_at",
        "appKey",
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->using(PromoCode::class);
    }
}

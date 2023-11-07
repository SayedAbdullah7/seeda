<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PromoCodeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "user_id"=>$this->user_id,
            "promo_type"=>$this->PromoCode->promo_type,
            "title"=>$this->PromoCode->title,
            "code"=>$this->PromoCode->code,
            "type"=>$this->PromoCode->type,
            "discount"=>$this->PromoCode->discount,
            "num_of_use"=>$this->PromoCode->num_of_use,
            "num_of_apply"=>$this->num_of_apply,
            "min_amount"=>$this->PromoCode->min_amount,
            "max_amount"=>$this->PromoCode->max_amount,
            "start_at"=>$this->PromoCode->start_at,
            "expire_at"=>$this->PromoCode->expire_at,
            "appKey"=>$this->PromoCode->appKey,
        ];
    }
}

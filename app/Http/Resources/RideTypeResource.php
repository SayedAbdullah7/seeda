<?php

namespace App\Http\Resources;

use App\services\Map\MapService;
use App\services\order\priceCalculation;
use App\services\PromoCodeService;
use Illuminate\Http\Resources\Json\JsonResource;

class RideTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $distance = MapService::TwoPointDistanceDetails(request()->get("fromLat"),request()->get("fromLng"),request()->get("toLat"),request()->get("toLng"));

        $price = priceCalculation::calculation($this->price,$distance);
        $tax = priceCalculation::startTax($price,$this->id);
        $discountPrice = PromoCodeService::ApplyPromoCodeStart($price);

        if ($discountPrice == 0)
            $discount =0;
        else
            $discount =$price-$discountPrice;


        return [
            "id"=>$this->id,
            "name"=>$this->name,
            "price"=>number_format($tax["userPrice"],2,"."),
            "discount"=>number_format((($discount/$price)*100),2,"."),
            "discountValue"=>number_format($discount,2,"."),
            "userPrice"=>number_format(($tax["userPrice"]-$discount),2,"."),
            "distance"=>".23",
            "time"=>gmdate("H:i:s", $distance["duration"]),
            "image"=>$this->image
        ];
    }
}

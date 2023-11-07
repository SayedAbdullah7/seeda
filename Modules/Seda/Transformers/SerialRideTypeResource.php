<?php

namespace Modules\Seda\Transformers;

use App\services\Map\MapService;
use App\services\order\priceCalculation;
use App\services\PromoCodeService;
use Illuminate\Http\Resources\Json\JsonResource;

class SerialRideTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $toLat = request()->get("toLat");
        $toLng = request()->get("toLng");

        $Outdistance=[
            "distance"  => 0,
            "duration"  => 0,
            "traffic_duration"  => 0
        ];
        for($i=0;$i<count($toLat);$i++){
            $previous = $i;
            if($i == 0){
                $fromLat = request()->get("fromLat");
                $fromLng = request()->get("fromLng");
            }else{
                $fromLat = $toLat[$previous-1];
                $fromLng = $toLng[$previous-1];
            }
            $distance = MapService::TwoPointDistanceDetails($fromLat,$fromLng,$toLat[$i],$toLng[$i]);

            $Outdistance["distance"] = $Outdistance["distance"] + $distance["distance"];
            $Outdistance["duration"] = $Outdistance["duration"] + $distance["duration"];
            $Outdistance["traffic_duration"] = $Outdistance["traffic_duration"] + $distance["traffic_duration"];
        }

        $price = priceCalculation::calculation($this->price,$Outdistance);
        $tax = priceCalculation::startTax($price,$this->id);
        $discountPrice = PromoCodeService::ApplyPromoCodeStart($price);

        if ($discountPrice == 0)
            $discount =0;
        else
            $discount =$price-$discountPrice;


        return [
            "id"=>$this->id,
            "shipment_type_id"=>2,
            "name"=>"just Go ".$this->name,
            "price"=>number_format($tax["userPrice"],2,"."),
            "discount"=>number_format((($discount/$price)*100),2,"."),
            "discountValue"=>number_format($discount,2,"."),
            "userPrice"=>number_format(($tax["userPrice"]-$discount),2,"."),
            "distance"=>".23",
            "title"=>"",//$this->RideTypes->title
            "des"=>"",//$this->RideTypes->des
            "time"=>gmdate("H:i:s", $distance["duration"]),
            "image"=>$this->Medias->filename??null
        ];
    }
}

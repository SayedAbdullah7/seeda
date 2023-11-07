<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use function PHPUnit\Framework\isEmpty;

class ScooterResource extends JsonResource
{

    public function toArray($request)
    {

        $distance = $this->getDistanceBetweenPointsNew(request()->get('latitude'),request()->get('longitude'),$this->locations->latitude,$this->locations->longitude);
        return [
            "id"=>$this->id,
            "distance"=>$distance,
            "last_image"=>(count($this->order)> 0 && $this->order->last()->medias->first() !== null)?$this->order->last()->medias->where("type","orderEnd")->first()->filename:null,
            "battery"=>intval($this->battary),
            "ride_types_id"=>$this->ride_types_id,
            "vehicles_color"=>$this->vehicles_color,
            "locations"=>new locationResource($this->locations),
        ];
    }



    private function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2) {
        $theta = $longitude1 - $longitude2;
        $distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
        $distance = acos($distance);
        $distance = rad2deg($distance);
        $distance = $distance * 60 * 1.1515;

        return (round($distance* 1.609344*1000,2));
    }
}

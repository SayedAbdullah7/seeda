<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class hoursRideTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "shipment_type_id"=>6,
            "id"=>$this->RideTypes->id,
            "name"=>"hours ".$this->RideTypes->name,
            "title"=>"",//$this->RideTypes->title
            "des"=>"",//$this->RideTypes->des
            "move_minute_price"=>$this->move_minute_price,
        ];
    }
}

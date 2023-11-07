<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GetNearestCarResource extends JsonResource
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
            "latitude"=>(double)$this->Activelocations->latitude,
            "longitude"=>(double)$this->Activelocations->longitude,
            "direction"=>number_format($this->Activelocations->direction,3,"."),
        ];
    }
}

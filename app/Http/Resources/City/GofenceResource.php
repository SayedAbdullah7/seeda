<?php

namespace App\Http\Resources\City;

use Illuminate\Http\Resources\Json\JsonResource;

class GofenceResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "id"=> 1,
            "geofenceable_type"=> $this->geofenceable_id,
            "geofenceable_id"=> $this->geofenceable_id,
            "latitude"=> $this->latitude,
            "longitude"=> $this->longitude,
            "address"=> $this->address,
            "type"=> $this->type,
        ];
    }
}

<?php

namespace App\Http\Resources\City;

use Illuminate\Http\Resources\Json\JsonResource;

class CityGofenceResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "id"=> $this->id,
            "country_id"=> $this->country_id,
            "city"=> $this->city,
            "geofence"=> GofenceResource::collection($this->geofence)
        ];
    }
}

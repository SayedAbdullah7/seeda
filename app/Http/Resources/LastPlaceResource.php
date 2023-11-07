<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LastPlaceResource extends JsonResource
{
    public function toArray($request)
    {
        $location = $this->locations->where('type','to')->first();
        return [
            'id'=>$location->id,
            'created_at'=>$location->created_at->timestamp,
            'created_at_str'=>$location->created_at->format('Y-m-d h:i:s'),
            'type'=>$location->type,
            'longitude'=>$location->longitude,
            'latitude'=>$location->latitude,
            'address'=>$location->address,
            'title'=>$location->title,
        ];
    }
}

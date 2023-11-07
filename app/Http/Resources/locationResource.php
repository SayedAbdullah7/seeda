<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class locationResource extends JsonResource
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
            'id'=>$this->id,
            'created_at'=>$this->created_at->timestamp,
            'created_at_str'=>$this->created_at->format('Y-m-d h:i:s'),
            'type'=>$this->type,
            'longitude'=>(double)$this->longitude,
            'latitude'=>(double)$this->latitude,
            'address'=>$this->address,
            'title'=>$this->title,
        ];
    }
}

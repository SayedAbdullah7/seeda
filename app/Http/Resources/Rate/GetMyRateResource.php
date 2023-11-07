<?php

namespace App\Http\Resources\Rate;

use Illuminate\Http\Resources\Json\JsonResource;

class GetMyRateResource extends JsonResource
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
            'rate'=> $this->rate,
            'comment'=>$this->comment,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class historyOrderCollection extends JsonResource
{

    public function toArray($request)
    {

        $fromLocation= $this->locations->where('type','from')->first();
        $toLocation= $this->locations->where('type','to')->first();
        return [
            'id'=>$this->id,
            'status'=>$this->status,
            'Rate'=>$this->driver->getRateAsDriverAttribute?->sum("rate"),
            'price'=>$this->OrderDetails->userPrice,
            'fromLocation'=>$fromLocation?new locationResource($this->locations->where('type','from')->first()):[],
            'toLocation'=>$toLocation? locationResource::collection($this->locations->where('type','to')):[],
        ];
    }
}

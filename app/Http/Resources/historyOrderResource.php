<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class historyOrderResource extends JsonResource
{


    public function toArray($request)
    {
        $fromLocation= $this->locations->where('type','from')->first();
        $toLocation= $this->locations->where('type','to')->first();
        return [
            'id'=>$this->id,
            'created_at'=>$this->created_at->timestamp,
            'created_at_str'=>$this->created_at->format('Y-m-d h:i:s'),
            'status'=>$this->status,
            'user'=>new userResource($this->user),
            'price'=>100,
            'discount'=>'50%',
            'payment_type_id'=>$this->payment_type_id,
            'priceAfterDiscount'=>50,
            'driver'=>$this->driver?new userResource($this->driver->user):[],
            'fromLocation'=>$fromLocation?new locationResource($this->locations->where('type','from')->first()):[],
            'toLocation'=>$toLocation?new locationResource($this->locations->where('type','to')->first()):[],
        ];
    }
}

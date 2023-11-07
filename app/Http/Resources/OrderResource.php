<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
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
            'payment_type_id'=>(int)$this->payment_type_id,
            'hours'=>$this->hours,

            'time_taken'=>($this->OrderDetails== null)?null:$this->OrderDetails->time_taken,
            'arrived_time'=>($this->OrderDetails== null)?null:$this->OrderDetails->arrived_time,
            'price'=>($this->OrderDetails == null)?null:number_format($this->OrderDetails->price,"2","."),
            'discount'=>($this->OrderDetails == null)?null:number_format($this->OrderDetails->discount,"2","."),
            'distance'=>($this->OrderDetails == null)?null:$this->OrderDetails->distance,
            'captainTax'=>($this->OrderDetails == null)?null:number_format($this->OrderDetails->captainTax,"2","."),
            'userTax'=>($this->OrderDetails == null)?null:number_format($this->OrderDetails->userTax,"2","."),
            'captainPrice'=>($this->OrderDetails == null)?null:number_format($this->OrderDetails->captainPrice,"2","."),
            'userPrice'=>($this->OrderDetails== null)?null:number_format($this->OrderDetails->userPrice,"2","."),

            'captain'=>$this->driver?new userResource($this->driver->user):null,
            'fromLocation'=>$fromLocation?new locationResource($this->locations->where('type','from')->first()):[],
            'toLocation'=>$toLocation?locationResource::collection($this->locations->where('type','to')):[],
            'Vehicles'=>$this->driver?new VehiclesResource($this->driver->user->Vehicles):null,
        ];
    }
}

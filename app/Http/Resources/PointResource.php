<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PointResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "id"=>$this->id,
            "points"=>number_format($this->points,2,'.'),
            "transaction"=>TransactionResource::collection($this->Transaction)
        ];
    }
}

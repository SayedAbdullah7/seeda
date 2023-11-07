<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "id"=>$this->id,
            "amount"=>number_format($this->amount,2,'.'),
            "type"=>$this->type,
        ];
    }
}

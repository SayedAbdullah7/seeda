<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "id"=>$this->id,
            "balance"=>number_format($this->balance,2,'.'),
            "transaction"=>TransactionResource::collection($this->Transaction)
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "id"=> $this->id,
            "user_id"=> $this->cardable_id,
            "cardDigits"=> $this->cardDigits,
        ];
    }
}

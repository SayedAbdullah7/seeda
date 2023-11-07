<?php

namespace Modules\Seda\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class EaringResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "user_id"=>$this->user_id,
            "trips_num"=>$this->trips_num,
            "captainPrice"=>$this->captainPrice,
            "captainTax"=>$this->captainTax,
            "earing"=>$this->earing,
            "discount"=>$this->discount,
            "hours"=>$this->hours,
            "day"=>$this->day,
            "day_str"=>$this->day_str,
        ];
    }
}

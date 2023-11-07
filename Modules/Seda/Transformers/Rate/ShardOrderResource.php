<?php

namespace Modules\Seda\Transformers\Rate;

use Illuminate\Http\Resources\Json\JsonResource;

class ShardOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}

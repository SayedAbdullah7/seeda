<?php

namespace Modules\Seda\Transformers\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class AddMoneyToWalletAfterPayRequest extends JsonResource
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

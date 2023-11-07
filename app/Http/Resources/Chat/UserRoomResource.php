<?php

namespace App\Http\Resources\Chat;

use App\Http\Resources\userResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserRoomResource extends JsonResource
{

    public function toArray($request)
    {

        return [
            $this->User->type=>new userResource($this->User),
        ];
    }
}

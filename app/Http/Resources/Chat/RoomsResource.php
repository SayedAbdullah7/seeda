<?php

namespace App\Http\Resources\Chat;

use App\Http\Resources\userResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomsResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'type'=>$this->type,
            'user'=>UserRoomResource::collection($this->UserRooms),
        ];
    }
}

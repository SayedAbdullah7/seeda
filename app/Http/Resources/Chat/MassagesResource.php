<?php

namespace App\Http\Resources\Chat;

use App\Http\Resources\Media\MediaResource;
use App\Http\Resources\userResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MassagesResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'from_user_if'=>$this->user_id,
            'to_user_id'=>$this->to_user_id,
            'created_at'=>$this->created_at->timestamp,
            'created_at_str'=>$this->created_at->format('Y-m-d h:i:s'),
            'id'=>$this->id,
            'room_id'=>$this->room_id,
            'massage'=>(string)$this->message,
            'user'=>new userResource($this->User),
            'medias'=>MediaResource::make($this->medias),
        ];
    }
}

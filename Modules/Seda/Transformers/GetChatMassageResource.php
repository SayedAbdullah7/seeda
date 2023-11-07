<?php

namespace Modules\Seda\Transformers;

use App\Http\Resources\Media\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GetChatMassageResource extends JsonResource
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
            'id'=>$this->id,
            'room_id'=>$this->room_id,
            'from_user_if'=>$this->user_id,
            'to_user_id'=>$this->to_user_id,
            'massage'=>(string)$this->message,
            'created_at'=>$this->created_at->timestamp,
            'created_at_str'=>$this->created_at->format('Y-m-d h:i:s'),
            'medias'=>MediaResource::make($this->medias),
        ];
    }
}

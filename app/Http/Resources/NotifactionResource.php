<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotifactionResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            "id"=>$this->id,
            "user_id"=>$this->user_id,
            "title"=>$this->notification->title,
            "massage"=>$this->notification->content,
            "is_seen"=>$this->is_seen,
            "notification_id"=>$this->notification->id,
            'created_at'=>$this->created_at->timestamp,
            'created_at_str'=>$this->created_at->format('Y-m-d h:i:s'),
        ];
    }
}

<?php

namespace App\Http\Resources\Media;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "id"=>$this->id,
            "filename"=>$this->filename,
            "filetype"=>$this->filetype,
            "type"=>$this->type,
            "createBy_id"=>$this->createBy_id,
            "createBy_type"=>$this->createBy_type,
            'created_at'=>$this->created_at->timestamp,
            'created_at_str'=>$this->created_at->format('Y-m-d h:i:s'),
        ];
    }
}

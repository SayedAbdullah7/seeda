<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class userResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id"=>$this->id,
            "name"=>$this->name??null,
            "phone"=>$this->phone,
            "nickName"=> $this->nickName,
            "birth"=> $this->birth,
            "email"=> $this->email,
            "id_number"=> $this->id_number??"",
            "address"=> $this->address??"",
            "type"=> $this->type,
            "userName"=> $this->userName,
            "is_online"=> (bool)$this->is_online,
            "rate"=> number_format($this->rates->sum('rate')/((count($this->rates) == 0)?1:count($this->rates)), 2, '.', ' '),
            "image"=> $this->medias->where("type","image")->first()->filename??null,
            "driverImages"=> count($this->medias->where("type","driverImages")->pluck("filename","Second_type"))>0
                ?$this->medias->where("type","driverImages")->pluck("filename","Second_type"):null,
        ];
    }
}

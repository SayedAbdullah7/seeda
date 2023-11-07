<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VehiclesResource extends JsonResource
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
            "vehicle_types_company"=>$this->VehicleTypes->company,
            "vehicle_types_type"=>$this->VehicleTypes->type,
            "vehicle_types_model"=>$this->VehicleTypes->model,
            "Vehicle_color_name"=>$this->VehicleColor->name,
            "Vehicle_color_code"=>$this->VehicleColor->code,
            "purchase_year"=>$this->purchase_year,
            "car_number"=>$this->car_number,
            "ride_types_id"=>$this->ride_types_id,
        ];
    }
}

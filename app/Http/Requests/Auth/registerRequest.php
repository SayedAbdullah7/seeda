<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class registerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        if ($this->has("social") && $this->social){
            return [
                'driver_images'=>"required_if:userDetails.type,==,captain|array",
                'gender'=>"string",
                'vehicle'=>"required_if:userDetails.type,==,captain|array",
                'vehicle.vehicle_types_id'=>"required_if:userDetails.type,==,captain|integer",
                'vehicle.Vehicle_color_id'=>"required_if:userDetails.type,==,captain|integer|exists:vehicle_colors,id",
                'vehicle.car_number'=>"required_if:userDetails.type,==,captain|string",
                'vehicle.purchase_year'=>"required_if:userDetails.type,==,captain|string",
                'vehicle.images'=>"required_if:userDetails.type,==,captain|array",
                'vehicle.vehicles_color'=>"string",
                'driver.*'=>"file"
            ];
        }else{
            return [
                'image'=>'nullable',
                'name'=>'required',
                'gender'=>"string",
                'nickName'=>'nullable',
                'birth'=>'nullable',
                'email'=>'nullable|'.Rule::unique("users","email")->where("appKey",appKey()),
                'driver_images'=>"required_if:userDetails.type,==,captain|array",
                'vehicle'=>"required_if:userDetails.type,==,captain|array",
                'vehicle.vehicle_types_id'=>"required_if:userDetails.type,==,captain|integer",
                'vehicle.Vehicle_color_id'=>"required_if:userDetails.type,==,captain|integer|exists:vehicle_colors,id",
                'vehicle.car_number'=>"required_if:userDetails.type,==,captain|string",
                'vehicle.vehicles_color'=>"string",
                'vehicle.purchase_year'=>"required_if:userDetails.type,==,captain|string",
                'vehicle.images'=>"required_if:userDetails.type,==,captain|array",
                'driver.*'=>"file"
            ];
        }
    }
}

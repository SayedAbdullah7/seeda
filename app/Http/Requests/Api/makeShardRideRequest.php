<?php

namespace App\Http\Requests\Api;

use App\Rules\doubleRule;
use Illuminate\Foundation\Http\FormRequest;

class makeShardRideRequest extends FormRequest
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
        return [
            'locationId'=>'required_if:fromLocation,',
            "points"=>"required|array",
            "points.*.longitude"=>['numeric',new doubleRule],
            "points.*.latitude"=>['numeric',new doubleRule],
            'fromLocation.longitude'=>['required_if:locationId,','required','numeric',new doubleRule],
            'fromLocation.latitude'=>['required','numeric',new doubleRule],
            'toLocation.longitude'=>['required','numeric',new doubleRule],
            'toLocation.latitude'=>['required','numeric',new doubleRule],
            'shipment_type_id'=>"required|exists:shipment_type,id",
            'payment_type_id'=>"required|integer|between:1,2",
            'ride_type_id'=>"integer",
            'passenger'=>"required|integer",
            'date'=>"required|date_format:Y-m-d H:i:s",
            'card_id'=>["nullable","integer"]
        ];
    }
}

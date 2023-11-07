<?php

namespace App\Http\Requests;

use App\Rules\doubleRule;
use Illuminate\Foundation\Http\FormRequest;

class makeOrderRequest extends FormRequest
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


    public function rules()
    {
        return [
            'locationId'=>'required_if:fromLocation,',
            'fromLocation.longitude'=>['required_if:locationId,','required','numeric',new doubleRule],
            'fromLocation.latitude'=>['required','numeric',new doubleRule],
            'toLocation.*.longitude'=>['required','numeric',new doubleRule],
            'toLocation.*.latitude'=>['required','numeric',new doubleRule],
            'shipment_type_id'=>"required|exists:shipment_type,id",
            'payment_type_id'=>"required|integer|between:1,3",
            'ride_type_id'=>"integer",
            'card_id'=>["required_if:payment_type_id,2","nullable","integer"]
        ];
    }
}

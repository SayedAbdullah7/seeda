<?php

namespace App\Http\Requests\Api\Order;

use App\Rules\doubleRule;
use Illuminate\Foundation\Http\FormRequest;

class EndOrderByDriverRequest extends FormRequest
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
            'orderId' =>'required|exists:orders,id',
            'toLocation.longitude'=>['numeric',new doubleRule],
            'toLocation.latitude'=>['numeric',new doubleRule],
            'long_line'=>['array'],
            'long_line.*'=>['numeric',new doubleRule],
            'lat_line'=>['required_with:long_line','array'],
            'lat_line.*'=>['numeric',new doubleRule],
        ];
    }
}

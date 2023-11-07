<?php

namespace App\Http\Requests\Api\Order;

use App\Rules\doubleRule;
use Illuminate\Foundation\Http\FormRequest;

class StartOrderByDriverRequest extends FormRequest
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
            'fromLocation.longitude'=>['numeric',new doubleRule],
            'fromLocation.latitude'=>['numeric',new doubleRule],
        ];
    }
}

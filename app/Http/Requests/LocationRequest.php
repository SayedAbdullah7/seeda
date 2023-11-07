<?php

namespace App\Http\Requests;

use App\Rules\doubleRule;
use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest
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
            'longitude'=>['required','numeric',new doubleRule],
            'latitude'=>['required','numeric',new doubleRule],
            'address'=>'required|string',
            'type'=>'required|string',
            'title'=>'string',
            'id'=>'nullable'
        ];
    }
}

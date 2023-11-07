<?php

namespace App\Http\Requests\Api;

use App\Rules\doubleRule;
use Illuminate\Foundation\Http\FormRequest;

class ActiveLocationRequest extends FormRequest
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
            'longitude'=>['required','numeric',new doubleRule],
            'latitude'=>['required','numeric',new doubleRule],
            'direction'=>['required','numeric'],
            'address'=>'string',
            'type'=>'string',
        ];
    }
}

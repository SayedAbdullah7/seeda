<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddRateRequest extends FormRequest
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
            'rateable_type'=>['required','string'],
            'rateable_id'=>['required','int'],
            'rate'=>'int|between:1,5',
            'comment'=>'nullable|string'
        ];
    }

    public function uniqueByAppKey($table)
    {
        return  Rule::unique($table)->where(function ($query)  {
            return $query->where('id', request()->id)
                ->where('appKey', request()->header('appKey'));
        });
    }

    public function passedValidation()
    {
        $data= array_merge($this->validated(),[
            'rateable_type'=>'App\Models\\'.request()->rateable_type,
            'rateable_id'=>request()->rateable_id,
            'user_id'=>$this->user()->id,
        ]);

        request()->merge(['validated'=>$data]);
    }
}

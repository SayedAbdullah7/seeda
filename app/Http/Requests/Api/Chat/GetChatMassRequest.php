<?php

namespace App\Http\Requests\Api\Chat;

use App\Models\Rooms;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetChatMassRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            "order_id"=>"required|"
        ];
    }
}

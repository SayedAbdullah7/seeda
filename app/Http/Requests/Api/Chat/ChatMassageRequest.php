<?php

namespace App\Http\Requests\Api\Chat;

use App\Rules\userInRoomRule;
use Illuminate\Foundation\Http\FormRequest;

class ChatMassageRequest extends FormRequest
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
            "room_id"=>"required",
            "user_id"=>["required",new userInRoomRule],
//            "user_id"=>"required|".Rule::exists("users_in_rooms","user_id")->where("room_id",$this->room_id),
            "message"=>"string",
            "media.filename"=>"file",
            "media.filetype"=>"required_if:media.filename,|string",
            "media.type"=>"required_if:media.filename,|string",
        ];
    }
}

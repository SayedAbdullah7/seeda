<?php

namespace App\Http\Controllers\Api\Card;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SaveCardRequest;
use App\Integrations\payment\factory\StripSaveCard;
use App\Models\Card;
use App\Models\User;
use App\Response\ApiResponse;
use Illuminate\Http\Request;

class SaveCardController extends Controller
{
    public function index(SaveCardRequest $request)
    {
        $token = $this->saveCard($request->all());
        if ($token){
            $this->saveUserToken($token,substr($request->card_number, -4));
            return (new ApiResponse(200,__("api.card saved successfully"),[]))->send();
        }else{
            return (new ApiResponse(406,__("api.card invalid Card or  Card Balance Not Sufficient "),[]))->send();
        }
    }

    function saveCard( $data)
    {
        $data["token"]="sk_live_cpRfcChiPXqvpbO5PvIFLUr300uSfBJJCs";
        try {
            $token =(new StripSaveCard($data))->savecard();
        } catch (\Throwable $e) {
            return null;
        }

        return $token;
    }

    public function saveUserToken($token , $digits){
        $data  = [
            "cardable_type"=>User::class,
            "cardable_id"=>auth()->id(),
            "token"=>$token,
            "cardDigits"=>$digits,
            "appKey"=>appKey(),
        ];

        Card::create($data);
    }

}

<?php

namespace App\Http\Controllers\Api\Wallet;

use App\Enums\PaymentKey;
use App\Enums\PaymentWays;
use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\User;
use App\Response\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class chargeMyWalletController extends Controller
{
    public function index(Request $request){
        $request->validate([
            "amount"=>"required|numeric",
            "way"=>["required",new Enum(PaymentWays::class)],
            "key"=>["required",new Enum(PaymentKey::class)],
            "card_id"=>"required_if:way,==,card",
            "phone"=>"required_if:way,==,mobileWallet",
        ]);
//        dd("sd");

        $card = Card::find($request->card_id);
        $class= "\\App\\Integrations\\payment\\factory\\Paymob";
        if(class_exists($class)){
            $paymob =  new $class();
            if ($request->way == "mobileWallet")
                return $paymob->payWithPhone($request->phone,auth()->user(),$request->amount,$request->way,$request->key);

            $request->validate([
                "card_id"=>Rule::exists("cards","id")->where("cardable_id",auth()->id()),
            ]);
            return  $paymob->payWithToken($card,auth()->user(),$request->amount,$request->way,$request->key);
        }

        return (new ApiResponse(406,"some error Upload paymob pay",[]))->send();
    }
}

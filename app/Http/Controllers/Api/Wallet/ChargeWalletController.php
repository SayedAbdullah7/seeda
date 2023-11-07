<?php

namespace App\Http\Controllers\Api\Wallet;

use App\Http\Controllers\Controller;
use App\Response\ApiResponse;
use App\services\walletService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChargeWalletController extends Controller
{
    private walletService $walletService;

    public function __construct()
    {
        $this->walletService = new walletService();
    }

    public function index(Request $request){
        $data = $request->validate([
            "user_id"=>"required|".Rule::exists("users","id")->where("appKey",appKey()),
            "amount"=>"required|numeric"
        ]);

        $this->walletService->chargeToAnthoerUser($data["amount"],$data["user_id"]);
        return (new ApiResponse(200,__("api.moneyAddToWallet"),[]))->send();
    }
}

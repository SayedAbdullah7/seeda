<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AddMoneyToWalletAfterPayRequest;
use App\Models\Order;
use App\Response\ApiResponse;
use App\services\walletService;
use Illuminate\Http\Request;

class AddMoneyToWalletAfterPayController extends Controller
{
    private walletService $wallet;

    public function __construct(){
        $this->wallet = new walletService();
    }

    public function index(Request $request)
    {
        $order_id = $request->order_id;
        $amount = $request->amount;

        $order  = Order::with("OrderDetails")->where("id",$order_id)->first();
        if ($order->OrderDetails->userPrice > $amount){
            $response = new ApiResponse(406,__("api."),[]);
            return $response->send();
        }
        if ($order->payment_type_id == 3){
            $rsp = $this->wallet->AddRemainToWallet(($amount -$order->OrderDetails->userPrice),$order->user_id);

            if ($rsp){
                $response = new ApiResponse(200,__("api.remainAddToWallet"),[]);
            }else{
                $response = new ApiResponse(406,__("api.you enter invalid amount"),[]);
            }
        }else{
            $response = new ApiResponse(406,__("api.this api active for cash only"),[]);
        }
        return $response->send();
    }
}

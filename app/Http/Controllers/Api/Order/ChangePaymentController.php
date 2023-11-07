<?php

namespace App\Http\Controllers\Api\Order;

use App\Enums\orderStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Response\ApiResponse;
use App\services\walletService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChangePaymentController extends Controller
{
    public function index(Request $request){
        $request->validate([
           "order_id"=>"required|".Rule::exists("orders","id")->where("appKey",appKey())
                   ->where("user_id",auth()->id()),
           'payment_type_id'=>"required|integer|between:1,3",
           'card_id'=>["required_if:payment_type_id,2","nullable","integer"]
        ]);

        $order = Order::find($request->order_id);

        if ($this->CheckOrderStatus($order))
            return (new ApiResponse(406,__("api.yourOrderIsNotWettingYet"),[]))->send();

        if ($request->payment_type_id == 1 && $this->checkWalletAmount())
            return (new ApiResponse(406,__("api.SomeErrorHappen"),[]))->send();

        $data = ["payment_type_id"=>$request->payment_type_id];
        if ($request->payment_type_id == 2)
            $data += ["card_id"=>$request->card_id];
        $order->update($data);
        return (new ApiResponse(200,__("api.ChangedSuccessfully"),["order" => new OrderResource($order)]))->send();
    }

    private function CheckOrderStatus($order)
    {
        if ($order->status == orderStatus::waiting)
            return false;
        return true;
    }

    private function checkWalletAmount()
    {
        $userWalletAmount = (new walletService())->GetWalletBalance(auth()->id()) ;
        if ($userWalletAmount > 0)
            return false;
        return true;
    }
}

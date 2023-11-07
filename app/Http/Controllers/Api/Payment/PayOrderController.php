<?php

namespace App\Http\Controllers\Api\Payment;

use App\Enums\orderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentType;
use App\Response\ApiResponse;
use App\services\paymentService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PayOrderController extends Controller
{
    public function pay(Request $request){
        $data = $request->validate([
            "order_id"=>"required",
            "payment_type_id"=>"required|".Rule::exists("payment_types",'id')->where("appKey",appKey()),
        ]);

        $order = Order::find($request->order_id);

        if ($order->user_id != auth()->id()){
            $response = new ApiResponse(406,__("api.you don't have access on this order",),[]);
            return $response->send();
        }

        if ($order->status == orderStatus::cancel || $order->status == orderStatus::waiting){
            $response = new ApiResponse(406,__("api.order canceled , you can't complete pay",),[]);
            return $response->send();
        }

        $paymentType = PaymentType::find($data["payment_type_id"]);

        return paymentService::pay(5000,$paymentType,$order);
    }

    public function isPaid(Request $request){
        $data = $request->validate([
            "order_id"=>"required",
            "payment_type_id"=>"required",
        ]);
        $order = Order::find($request->order_id);
        $order->update(["payment_status"=>1]);
        $response = new ApiResponse(200,__('api.paid successfully',),[]);
        return $response->send();
    }

}

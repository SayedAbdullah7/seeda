<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Http\Resources\CheckOutResource;
use App\Models\Order;
use App\Response\ApiResponse;

class CheckOutController extends Controller
{
    public function getOrderData($id){
        $order = Order::with("OrderDetails")->find($id);
        $data = new CheckOutResource($order);
        $response = new ApiResponse(200,__("api.order checkOut"),[
           "order"=>$data
        ]);
        return $response->send();
    }
}

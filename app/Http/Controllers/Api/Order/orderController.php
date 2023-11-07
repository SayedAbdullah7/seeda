<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\Order;
use App\Response\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use function GuzzleHttp\Promise\all;

class orderController extends Controller
{
    public function store(Request $request){
        $order=$this->createOrder($request);
        return GeneralApiOrderFactory('makeOrderController',Request::class,$order);
    }

    public function createOrder($request) :Order
    {
        return   createDB(Order::class,[
            'user_id'=>auth()->user()->id,
            'status'=>"waiting",
            'shipment_type_id'=>$request->shipment_type_id,
            "created_at"=>now(),
            'appKey'=>env(request()->header('appKey')),
            'payment_type_id'=>$request->payment_type_id
        ]);
    }
}

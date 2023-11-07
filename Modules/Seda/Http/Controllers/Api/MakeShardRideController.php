<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Http\Resources\locationResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Response\ApiResponse;
use App\services\order\OrderDetailsService;
use App\services\order\priceCalculation;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Ozgo\Transformers\ShardOrderResource;

class MakeShardRideController extends Controller
{

    public function index(Request $request)
    {

//        $lastOrder = Order::where("user_id",auth()->id())->whereIn("status",[orderStatus::accept,orderStatus::waiting,orderStatus::start])->get();
//        if (count($lastOrder) > 0){
//            $response=  new ApiResponse(406,__('api.finishActiveOrderBeforeOrderNewOne'),[]);
//            return $response->send();
//        }
        $order=$this->createOrder($request);

        $this->CreateLocation($request->fromLocation,$order,'from');
        $this->CreateLocation($request->toLocation,$order,'to');
        foreach ($request->points as $point){
            $this->CreateLocation($point,$order,'point');
        }

        OrderDetailsService::Create($order->id);


        $response=  new ApiResponse(200,__('api.new_order'),[
            'order'=>new ShardOrderResource($order)
        ]);
        return $response->send();

    }

    public function createOrder($request) :Order
    {
        return   createDB(Order::class,[
            'user_id'=>auth()->user()->id,
            'status'=>"waiting",
            'shipment_type_id'=>$request->shipment_type_id,
            "created_at"=>now(),
            'appKey'=>env(request()->header('appKey')),
            'payment_type_id'=>$request->payment_type_id,
            'passenger'=>$request->passenger,
            'day'=>$request->date,
        ]);

    }
    public function CreateLocation(array $locationRequest, Order $order,string $type='') : locationResource
    {
        $location = $order->locations()->create($locationRequest+['type'=>$type]);
        return new locationResource($location);
    }
}

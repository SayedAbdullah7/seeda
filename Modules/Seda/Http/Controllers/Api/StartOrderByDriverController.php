<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\OrderSentToDriver;
use App\Response\ApiResponse;
use App\Models\Order;
use App\Events\generalEvent;
use App\services\order\OrderDetailsService;
use Illuminate\Http\Request;
use App\Enums\orderStatus;

class StartOrderByDriverController extends Controller
{
    public $request;
    public function index( Request $request)
    {
        $this->request= $request;
        $order= Order::find($request->orderId);
        if($this->checkDrvierCanStart()==false){
            $response = new ApiResponse(420,__('api.you cant start this order'),[]);
        }else{
            $this->StartOrderByDriver();
            OrderDetailsService::startTime($order->id);
            if ($request->has("fromLocation")){
                $this->updateLocation($order,$request->get("fromLocation"));
            }
            $msg="your request has been started successfully";
            $response = new ApiResponse(200,__('api.'.$msg),[]);
            new generalEvent(new OrderResource($order),[$order->user_id],'startOrder',__("api.".$msg));
            $order->update(["status"=>orderStatus::start]);
        }
        return $response->send();
    }

    private function checkDrvierCanStart() :bool
    {
        return  OrderSentToDriver::where('user_id',auth()->user()->id)
                    ->where('order_id',$this->request->orderId)
                    ->where('status',orderStatus::arrived)
                    ->count() > 0;
    }



    private function StartOrderByDriver()
    {
        return  OrderSentToDriver::where('order_id',$this->request->orderId)
                    ->where('status',orderStatus::arrived)
                    ->where('user_id',auth()->user()->id)
                    ->update(['status'=>orderStatus::start]);
    }

    private function updateLocation($order,$fromLocations)
    {
        $fromLocation= $order->locations->where('type','from')->first();
        $fromLocation->update($fromLocations);
    }
}

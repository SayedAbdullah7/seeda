<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Enums\orderStatus;
use App\Events\generalEventWithAppKey;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\userResource;
use App\Models\OrderDetails;
use App\Models\OrderSentToDriver;
use App\Response\ApiResponse;
use App\Models\Order;
use App\Events\generalEvent;
use App\services\order\OrderDetailsService;
use App\services\roomService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcceptOrderByDriverController extends Controller
{
    public $request;
    public function index( Request $request)
    {
        DB::table('jobs')->where("payload", "like", "%AutoNextDriverJob-{$request->orderId}-order_id%")->delete();

        $this->request= $request;
        $order= Order::find($request->orderId);
        if($this->checkDriverCanAccept() === false){
            $response = new ApiResponse(420,__('api.you cant accept this order'),[]);
        }elseif($this->checkOrderISAvailable() === false){
            $response = new ApiResponse(421,__('api.this order is not available'),[]);
        }else{
            $this->acceptOrderByDriver();
            roomService::startRoom("private",[$order->user_id,auth()->id()],$request->orderId);
            $order->update(["status"=>orderStatus::accept]);

//            $order->captain = new userResource(auth()->user());
//            $order->lat =$request->lat;
//            $order->lng = $request->lng;
//            $order->heading = $request->heading;
            OrderDetailsService::acceptTime($order->id);
            new generalEvent(new OrderResource($order),[$order->user_id],'acceptOrder',__("api.your request has been accepted successfully by driver"));
            $response = new ApiResponse(200,__('api.your request has been accepted successfully'),[]);
        }
        return $response->send();
    }

    private function checkDriverCanAccept() :bool
    {
        return  OrderSentToDriver::where('user_id',auth()->user()->id)
                ->where('order_id',$this->request->orderId)
                ->where('status','waiting')
                ->count() > 0;
    }

    private function checkOrderISAvailable() :bool
    {
        return  OrderSentToDriver::where('order_id',$this->request->orderId)
                ->where('status','accept')
                ->count() < 1;
    }

    private function acceptOrderByDriver()
    {
        return  OrderSentToDriver::where('order_id',$this->request->orderId)
            ->where('status','waiting')
            ->where('user_id',auth()->user()->id)
            ->update(['status'=>'accept']);
    }

    private function cancelOrderForOtherDrivers()
    {
        $drivers = OrderSentToDriver::where(["order_id"=>$this->request->orderId,"status"=>orderStatus::waiting])->where('user_id','!=',auth()->user()->id)->get();
        if ($drivers){
            foreach ($drivers as $driver){
                new generalEventWithAppKey($this->order,$this->order->appKey,[$driver->user_id],'AutoCancelDriver',__("api.order has been cancelled automatic "));
            }
            $drivers->update(["status"=>orderStatus::cancel]);
        }
    }
}

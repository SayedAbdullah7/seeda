<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AcceptOrderByDriverRequest;
use App\Models\OrderSentToDriver;
use App\Response\ApiResponse;
use App\Models\Order;
use App\Events\generalEvent;
use App\Http\Resources\OrderResource;
use App\Enums\orderStatus;
use App\services\orderCycle\sendSerialCycle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class CancelOrderByDriverController extends Controller
{
    public $request;
    public function index( Request $request)
    {
        DB::table('jobs')->where("payload", "like", "%AutoNextDriverJob-{$request->orderId}-order_id%")->delete();
        $this->request= $request;
        $order= Order::find($request->orderId);
        $orderResource= new OrderResource($order,$order->locations->first(),$order->locations->last());

        if($this->checkDrvierCanCancel()==false){
            $resposne = new ApiResponse(420,__('api.you cant cancel this order'),[]);
        }elseif($this->checkOrderISAvailable() === false){
            $resposne = new ApiResponse(421,__('api.this order is not available'),[]);
        }else{
            $checkFoundedNext = $this->cancelOrderByDriver($orderResource);
            if ($checkFoundedNext == false){
                $order->update([
                    'status'=>orderStatus::cancel,
                    'cancel_reason'=>$request->cancel_reason
                ]);
            }
            $resposne = new ApiResponse(200,__('api.your request has been cancelled successfully'),[]);
        }
        return $resposne->send();
    }

    private function checkDrvierCanCancel() :bool
    {
        return  OrderSentToDriver::where('user_id',auth()->user()->id)
                    ->where('order_id',$this->request->orderId)
                    ->whereIn('status',[
                        orderStatus::waiting,
                        orderStatus::accept,
                        orderStatus::arrived
                    ])
                    ->count() > 0;
    }

    private function checkOrderISAvailable() :bool
    {
        return  OrderSentToDriver::where('order_id',$this->request->orderId)
                                ->whereIn('status',[
                                    orderStatus::start,
                                ])
                                ->count() < 1;
    }

    private function cancelOrderByDriver($order)
    {
        if ($order->status == orderStatus::arrived){
            if (Carbon::parse($order->created_at)->addMinutes(4) > now()){
                OrderSentToDriver::where('order_id',$this->request->orderId)
                    ->whereIn('status',[
                        orderStatus::waiting,
                        orderStatus::accept,
                        orderStatus::arrived,
                    ])
                    ->where('user_id',auth()->user()->id)
                    ->update(['status'=>orderStatus::cancel]);
            }else{
                OrderSentToDriver::where('order_id',$this->request->orderId)
                    ->whereIn('status',[
                        orderStatus::waiting,
                        orderStatus::accept,
                        orderStatus::arrived,
                    ])
                    ->where('user_id',auth()->user()->id)
                    ->update(['status'=>orderStatus::cancelAfterTime]);
            }
            new generalEvent($order,[$order->user_id],'CancelAfterArrived',__("api.order has been cancelled automatic "));

            return  false;
        }else{
            OrderSentToDriver::where('order_id',$this->request->orderId)
                ->whereIn('status',[
                    orderStatus::waiting,
                    orderStatus::accept
                ])
                ->where('user_id',auth()->user()->id)
                ->update(['status'=>orderStatus::cancel]);
            $driver =OrderSentToDriver::where('order_id',$this->request->orderId)
                ->where('status',orderStatus::waiting)->pluck('user_id');
            if (count($driver) == 0){
                new generalEvent($order,[$order->user_id],'NoDriverAcceptedOrFounded',__("api.order has been cancelled automatic "));
                return false;
            }

            if ($order->shipment_type_id == 2 && $driver){
                sendSerialCycle::sendNext($order);
            }
            return true;
        }
    }
}

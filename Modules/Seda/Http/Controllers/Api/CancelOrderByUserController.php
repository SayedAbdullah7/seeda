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
use App\services\CancelOrder\CancelUserFees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CancelOrderByUserController extends Controller
{
    public $request;
    public function index( Request $request)
    {
        DB::table('jobs')->where("payload", "like", "%AutoNextDriverJob-{$request->orderId}-order_id%")->delete();

        $this->request= $request;
        $order= Order::find($request->orderId);
        $orderResource= new OrderResource($order,$order->locations->first(),$order->locations->last());

        if($this->checkUserCanCancel($order)==false){
            $resposne = new ApiResponse(420,__('api.you cant cancel this order'),[]);
            return $resposne->send();
        }
        if($this->checkUserCanCancelStatus($order)==false){
            $resposne = new ApiResponse(406,__('api.you cant cancel this order after start or end '),[]);
            return $resposne->send();
        }

        if (in_array($order->status,[orderStatus::accept,orderStatus::arrived]))
            CancelUserFees::UserCancelPenalty($order);
        $order->update([
            'status'=>orderStatus::cancel,
            'cancel_reason'=>$request->cancel_reason
        ]);
        $this->cancelOrderByUser($orderResource);

        $resposne = new ApiResponse(200,__('api.your request has been cancelled successfully'),[]);
        return $resposne->send();
    }

    private function checkUserCanCancel($order) :bool
    {
        return  $order->user_id == auth()->user()->id;
    }

    private function checkUserCanCancelStatus($order) :bool
    {
        if ($order->status == orderStatus::start||$order->status == orderStatus::end)
            return false;
        else
            return true;
    }

    private function cancelOrderByUser($order)
    {
        $driver =OrderSentToDriver::where('order_id',$this->request->orderId)
            ->whereIn('status',[orderStatus::accept,orderStatus::arrived])->pluck('user_id');
        if ($driver){
            new generalEvent($order,$driver->toArray(),'cancelOrderByUser',__("api.order has been cancelled by user"));
        }
        if ($order->shipment_type_id == 3){
            $driver1 =OrderSentToDriver::where('order_id',$this->request->orderId)
                ->where('status',orderStatus::waiting)->pluck('user_id')->toArray();
            if (count($driver->toArray()) > 0)
                new generalEvent($order,$driver1,'cancelOrderByUser',__("api.order has been cancelled by user"));
        }


        if ($order->shipment_type_id == 2){
            $driver1 =OrderSentToDriver::where('order_id',$this->request->orderId)
                ->where('status',orderStatus::waiting)->pluck('user_id')->toArray();
            if (count($driver1) > 0)
                new generalEvent($order,[$driver1[0]],'cancelOrderByUser',__("api.order has been cancelled by user"));
        }

        return  OrderSentToDriver::where('order_id',$this->request->orderId)
                    ->update(['status'=>orderStatus::cancel]);
//        OrderSentToDriverservice::SentToDriver($order);
//        $drivers= OrderSentToDriver::where('order_id',$this->request->orderId)->pluck('driver_id');
//        new generalEvent($order,$drivers->toArray(),'cancelOrderByUser',__("api.order has been cancelled by user"));
    }

}

<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Enums\orderStatus;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderSentToDriver;
use App\Response\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RefuseOrderController extends Controller
{

    public $request;
    public function index( Request $request)
    {
        $this->request= $request;
        $order= Order::find($request->orderId);
        $orderResource= new OrderResource($order,$order->locations->first(),$order->locations->last());


        $this->cancelOrderByDriver($orderResource);
        $response = new ApiResponse(200,__('api.your request has been orderedRefused successfully'),[]);

        return $response->send();
    }

    private function cancelOrderByDriver($order)
    {
        $resp =  OrderSentToDriver::where('order_id',$this->request->orderId)->whereIn('status',[
                orderStatus::waiting,
                orderStatus::accept
            ])->where('user_id',auth()->user()->id)->update(['status'=>orderStatus::cancel]);


        OrderSentToDriverservice::sendNext($order);

        return $resp;
    }
}

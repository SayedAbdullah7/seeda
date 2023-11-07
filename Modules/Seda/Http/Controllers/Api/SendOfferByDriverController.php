<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Events\generalEvent;
use App\Http\Resources\userResource;
use App\Models\Order;
use App\Models\OrderSentToDriver;
use App\Response\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SendOfferByDriverController extends Controller
{
    public $request;
    public function index( Request $request)
    {
        $this->request= $request;
        $order= Order::find($request->order_id);

        if($this->checkOrderISAvailable() === false){
            $response = new ApiResponse(421,__('api.this order is not available'),[]);
        }else{
            $order_sent_to_driver_id = $this->OfferOrderByDriver();
            $order->order_sent_to_driver_id = $order_sent_to_driver_id;
            $order->driver = new userResource(auth()->user());
            $order->driver_price = $request->price;
            new generalEvent($order,[$order->user_id],'driverOffer',__("api.your request has been accepted successfully by driver"));
            $response = new ApiResponse(200,__('api.your request has been accepted successfully'),[]);
        }
        return $response->send();
    }


    private function checkOrderISAvailable() :bool
    {
        return  Order::where('id',$this->request->order_id)
                ->where('status','accept')
                ->count() < 1;
    }

    private function OfferOrderByDriver()
    {
        $order = OrderSentToDriver::where('order_id',$this->request->order_id)
            ->where('status','waiting')
            ->where('user_id',auth()->user()->id)->first();

        $order->update(['offer_price'=>$this->request->price]);

        return $order->id;
    }
}

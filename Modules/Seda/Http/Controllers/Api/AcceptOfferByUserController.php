<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Enums\orderStatus;
use App\Events\generalEvent;
//use App\Http\Requests\AcceptOfferByUserRequest;
use App\Http\Resources\userResource;
use App\Models\Order;
use App\Models\OrderSentToDriver;
use App\Models\User;
use App\Response\ApiResponse;
use App\services\order\OrderDetailsService;
use App\services\roomService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AcceptOfferByUserController extends Controller
{
    public $request;
    public function index( Request $request)
    {
        $this->request= $request;
        $order= Order::find($request->order_id);

        $driver_id = $this->acceptOrderByDriver();
        roomService::startRoom("private",[$driver_id,auth()->id()],$request->order_id);
        $order->update(["status"=>orderStatus::accept]);
        $driver = User::find($driver_id);
        $order->driver = new userResource($driver);
//        $order->lat =$request->lat;
//        $order->lng = $request->lng;
//        $order->heading = $request->heading;
        OrderDetailsService::acceptTime($order->id);
        new generalEvent($order,[$driver_id],'acceptOrder',__("api.your request has been accepted successfully by driver"));
        $response = new ApiResponse(200,__('api.your request has been accepted successfully'),[]);

        return $response->send();
    }


    private function acceptOrderByDriver()
    {
        $order = OrderSentToDriver::find($this->request->order_sent_to_driver_id);

        $order->update(['status'=>'accept']);

        return $order->user_id;
    }
}

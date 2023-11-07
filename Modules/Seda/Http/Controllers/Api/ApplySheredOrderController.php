<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Models\Order;
use App\Models\orderPassenger;
use App\Response\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ApplySheredOrderController extends Controller
{
    public function index(Request $request){
        $order = Order::with("orderPassenger")->find($request->order_id);
        $check = $this->check($order);
        if (!$check){
            return (new ApiResponse(406,__("api.don'tHaveValidPlace"),[]))->send();
        }

        $checkPassenger = $this->checkPassnger($order,$request->passenger);
        if (!$checkPassenger){
            return (new ApiResponse(406,__("api.remainSeatIsLow"),[]))->send();
        }
        $this->created($request->all(),$order);
        return (new ApiResponse(200,__("api.appliedSuccessfully"),[]))->send();
    }

    public function check($order){
        $passenger= $order->orderPassenger;

        if ($passenger != null){
            if ($passenger->sum("passenger") >= $order->passenger)
                return false;
        }
        return true;
    }

    public function checkPassnger($order,$request_passenger){
        $passenger= $order->orderPassenger;

        if ($passenger != null){
             $check = $order->passenger - $passenger->sum("passenger");
             if ($check < $request_passenger)
                 return  false;
        }
        return true;
    }

    private function created($data,$order)
    {
        orderPassenger::create([
            "user_id"=>auth()->id(),
            "order_id"=>$order->id,
            "passenger"=>$data["passenger"],
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api\Order;

use App\Events\generalEvent;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Rules\doubleRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class OrderLiveTracking extends Controller
{
    public function index(Request $request){
        $data = $request->validate([
            "order_id"=>["required",Rule::exists("orders","id")->where("appKey",appKey())],
            "lat"=>["required",new doubleRule()],
            "lng"=>["required",new doubleRule()],
        ]);
        $order = Order::find($request->order_id);
        new generalEvent($data,[$order->user_id],'userLiveTracking',__("api.order has been cancelled automatic "));
    }
}

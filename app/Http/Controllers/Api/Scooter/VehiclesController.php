<?php

namespace App\Http\Controllers\Api\Scooter;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Vehicles;
use App\Response\ApiResponse;
use App\services\Gps\sdnService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VehiclesController extends Controller
{
    public function index(Request $request){
        $request->validate([
            "status"=>"required|in:0,1",
            "command"=>"required",
            "order_id"=>"required|".Rule::exists("orders","id"),
        ]);

        $order = Order::find($request->order_id);
        $vehicle= Vehicles::find($order->scooter_id);

        $vehicle->update(["order_ended"=>$request->status]);

        return (new ApiResponse(200,__("api.EndOrderController"),[]))->send();
    }
}

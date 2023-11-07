<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Enums\orderStatus;
use App\Models\Order;
use App\Models\orderPassenger;
use App\Response\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class StratShardOrderController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();
        orderPassenger::where("order_id",$data["order_id"])->update(["status"=>orderStatus::start]);
        $order = Order::find($data["order_id"]);
        $order->update(["status"=>orderStatus::start]);
        return (new ApiResponse(200,__("api.OrderEndedSuccessfully"),[]))->send();
    }
}

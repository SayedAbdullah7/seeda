<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Enums\orderStatus;
use App\Models\Order;
use App\Response\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;

class CancelSharedController extends Controller
{

    public function index(Request $request)
    {
        $request->validate([
            "order_id"=>"required|".Rule::exists("orders","id")->where("appKey",appKey())
        ]);
        $order = Order::find($request->order_id);
        if ($this->checkStartedOrder($order)){
            return (new ApiResponse(406,__("api.youCan'tEndOrderAfterStart"),[]))->send();
        }

        $order->update(["status"=>orderStatus::cancel]);

        return (new ApiResponse(200,__("api.ShardRideOrderCanceledSuccessfully"),[]))->send();
    }

    private function checkStartedOrder($order)
    {
        if ($order->status == orderStatus::start || $order->status == orderStatus::end)
            return true;

        return false;
    }
}

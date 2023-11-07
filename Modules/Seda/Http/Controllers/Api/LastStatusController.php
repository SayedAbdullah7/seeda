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

class LastStatusController extends Controller
{

    public function index()
    {
        if (auth()->user()->type == "user"){
            $lastOrder = Order::whereIn("status",[orderStatus::accept,orderStatus::arrived,orderStatus::waiting,orderStatus::start,orderStatus::end,orderStatus::cancel])
                ->with(['user'])->whereBelongsTo(auth()->user())->first();
        }else{
            $orderSentToDriver = OrderSentToDriver::whereIn("status",[orderStatus::accept,orderStatus::start,orderStatus::arrived,orderStatus::end])
                ->where("user_id",auth()->id())->first();
            if ($orderSentToDriver)
                $lastOrder = Order::find($orderSentToDriver->order_id);
            else
                $lastOrder = null;
        }


        if (!$lastOrder){
            $response=  new ApiResponse(200,__("api.YouDontHaveAnActiveOrder"),[
                "active"=>false,
                "order"=>null,
            ]);
        }else{
            $response=  new ApiResponse(200,__('api.yourActiveOrderIs'),[
                "active"=>true,
                "order"=>new OrderResource($lastOrder)
            ]);
        }

        return $response->send();
    }
}

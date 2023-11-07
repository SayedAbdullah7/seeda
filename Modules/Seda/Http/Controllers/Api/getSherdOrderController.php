<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Models\Order;
use App\Response\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Ozgo\Transformers\ShardOrderResource;

class getSherdOrderController extends Controller
{

    public function index()
    {
        $orders = Order::with("orderPassenger")->where("shipment_type_id",4)->get();
        $response=  new ApiResponse(200,__('api.new_order'),[
            'order'=>ShardOrderResource::collection($orders)
        ]);
        return $response->send();
    }

}

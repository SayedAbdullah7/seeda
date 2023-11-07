<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Response\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ShowOrderController extends Controller
{
    public function index(Request $request)
    {
        $order_cancel = Order::find($request->id);
        $data = new OrderResource($order_cancel);
        $user_ids = [
            $order_cancel->user_id,
            $order_cancel->driver->user_id??0
        ];

        if ( !in_array(auth()->id(),$user_ids)){
            return (new ApiResponse(405,"you don't have the right to get this data",[]))->send();
        }
        return (new ApiResponse(200,"you order retrieved successfully",[
            'orders'=>$data
        ]))->send();
    }
}

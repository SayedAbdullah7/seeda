<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Models\Order;
use App\Response\ApiResponse;
use Illuminate\Routing\Controller;
use Modules\Ozgo\Transformers\ShardOrderResource;

class getMySherdOrderController extends Controller
{
    public function index()
    {
        $orders = Order::where("shipment_type_id",4)->with(['user','orderPassenger'])
            ->whereBelongsTo(auth()->user())
            ->orwhere(function($q){
                return $q->whereHas('orderPassenger',function($q){
                    return $q->where('user_id',auth()->user()->id);
                });
            })->get();
        $response=  new ApiResponse(200,__('api.new_order'),[
            'order'=>ShardOrderResource::collection($orders)
        ]);
        return $response->send();
    }
}

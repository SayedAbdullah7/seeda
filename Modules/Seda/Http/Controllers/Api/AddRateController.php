<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Enums\orderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderSentToDriver;
use App\Models\User;
use Modules\Ozgo\Transformers\Api\RateResource;
use App\Response\ApiResponse;
use App\Models\Rate;
use App\Events\Order\AcceptOrderEvent;
use Illuminate\Http\Request;

class AddRateController extends Controller
{
    public $request;
    public function index( Request $request)
    {
        $request->rateable_type= (strtoupper($request->rateable_type)=="USER")?User::class:Order::class;

        $checkRate = Rate::where("rateable_type",$request->rateable_type)->where("rateable_id",$request->rateable_id)
            ->where("user_id",auth()->id());

        if ($checkRate->count() > 0 ){
            $checkRate->update($request->validated);
            $response = new ApiResponse(200,__('api.you edit Rate '),[]);
            return $response->send();
        }

        if ($request->rateable_id == auth()->id()){
            $response = new ApiResponse(405,__('api.you not able to rate your self'),[]);
            return $response->send();
        }

        if ($request->rateable_type == User::class){
            $request->validate([
                "rateable_id"=>"exists:users,id"
            ]);
        }

        if ($request->rateable_type == Order::class){
            $request->validate([
                "rateable_id"=>"exists:orders,id"
            ]);
            $resp = $this->ordercheck($request->rateable_id);
            switch ($resp){
                case 1:
                    $response = new ApiResponse(405,__('api.you not able to rate canceled or waiting order'),[]);
                    return $response->send();
                case 2:
                    $response = new ApiResponse(405,__("api.you don't have the right to rate order"),[]);
                    return $response->send();
            }
        }

        Rate::create($request->validated);

        $response = new ApiResponse(200,__('api.rated accepted successfully'),[]);
        return $response->send();
    }



    public function ordercheck($rateable_id){
        $order_cancel = Order::find($rateable_id);
        if ($order_cancel->status == orderStatus::cancel || $order_cancel->status == orderStatus::waiting){
            return 1;
        }

        $user_ids = [
            $order_cancel->user_id,
            $order_cancel->driver->user_id??0
        ];

        if ( !in_array(auth()->id(),$user_ids)){
            return 2;
        }
        return 3;
    }
}

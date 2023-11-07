<?php

namespace App\Http\Controllers\Api\Order;

use App\Enums\orderStatus;
use App\Events\generalEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\locationResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderSentToDriver;
use App\Models\User;
use App\Response\ApiResponse;
use App\Rules\doubleRule;
use App\services\order\priceCalculation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AddNewDropOffController extends Controller
{
    public function index(Request $request){
        $request->validate([
            "order_id"=>"required|".Rule::exists("orders","id")->where("appKey",appKey()),
            'location.longitude'=>['required','numeric',new doubleRule],
            'location.latitude'=>['required','numeric',new doubleRule],
        ]);
        $order = Order::find($request->order_id);
        $location = $this->CreateLocation($request->location,$order,'to');

        if ($order->status == orderStatus::end)
            return (new ApiResponse(406,__("api.don'tHaveTheRightToAddPoint"),[]))->send();

        if ($order->status == orderStatus::waiting){
            $driver_id = OrderSentToDriver::where("order_id",$request->order_id)->where("status",orderStatus::waiting)->first();
            $driver = User::with("Vehicles")->find($driver_id->user_id);
            if (in_array($order->shipment_type_id,[1,2])){
                priceCalculation::startCal($order,$driver->Vehicles->ride_types_id);
            }
        }else{
            $driver_id = OrderSentToDriver::where("order_id",$request->order_id)->where("status",$order->status)->first();
        }

        new generalEvent($location,[$driver_id->user_id],'userAddNewDropOff',__("api.userAddNewDropOff"));
        return (new ApiResponse(200,__("api.pointAddedSuccessfully"),["order" => new OrderResource($order)]))->send();
    }

    public function CreateLocation(array $locationRequest, Order $order,string $type='') : locationResource
    {
        $location = $order->locations()->create($locationRequest+['type'=>$type]);
        return new locationResource($location);
    }
}

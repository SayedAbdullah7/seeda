<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Enums\orderStatus;
use App\Events\generalEvent;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderSentToDriver;
use App\Response\ApiResponse;
use App\services\order\OrderDetailsService;
use App\services\order\priceCalculation;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;

class EndHoursOrderController extends Controller
{
    public function index(Request $request)
    {
       $request->validate([
           "order_id"=>"required|".Rule::exists("orders","id")->where("appKey",appKey())
       ]);

       $order = Order::find($request->order_id);
        if($this->checkDriverCanEnd()==false){
            $resposne = new ApiResponse(420,__('api.you cant end this order'),[]);
        }else{
            $this->EndOrderByDriver();

            if ($request->has("toLocation")){
                $this->updateLocation($order,$request->get("toLocation"));
            }

            if ($request->has("long_line")){
                $this->updateLineLocation($order,$request->get("long_line"),$request->get("lat_line"));
            }

            OrderDetailsService::endTime($order->id);
            if (in_array($order->shipment_type_id,[1,2])){
                priceCalculation::endCalculation($order);
            }else if($order->shipment_type_id == 3){
                priceCalculation::endOfferCalculation($order);
            }

            $order->update(["status"=>orderStatus::end]);
            $msg= __('api.your request has been ended successfully');
            $orderData = new OrderResource($order);
            $resposne = new ApiResponse(200, $msg,[$orderData]);
            new generalEvent($order,[$order->user_id],'endOrder',$msg);
        }
        return $resposne->send();
    }

    private function checkDriverCanEnd() :bool
    {
        return  OrderSentToDriver::where('user_id',auth()->user()->id)
                ->where('order_id',$this->request->orderId)
                ->where('status',orderStatus::start)
                ->count() > 0;
    }

    private function EndOrderByDriver()
    {
        return  OrderSentToDriver::where('order_id',$this->request->orderId)
            ->where('status',orderStatus::start)
            ->where('user_id',auth()->user()->id)
            ->update(['status'=>orderStatus::end]);
    }

    private function updateLocation($order, $toLocation)
    {
        $toLocations= $order->locations->where('type','to')->first();
        $toLocations->update($toLocation);
    }

    private function updateLineLocation($order,  $long_line,  $lat_line)
    {
        OrderDetailsService::updateLinPoints($order->id,$lat_line,$long_line);
    }
}

<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Enums\orderStatus;
use App\Events\generalEvent;
use App\Http\Resources\OrderResource;
use App\Jobs\ArrivedDriverAlert;
use App\Models\Order;
use App\Models\OrderSentToDriver;
use App\Response\ApiResponse;
use App\Rules\doubleRule;
use App\services\order\OrderDetailsService;
use App\services\roomService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DriverArrivedController extends Controller
{

    public $request;
    public function index( Request $request)
    {
        $request->validate([
            "orderId"=>["required",Rule::exists("orders","id")->where("appKey",appKey())],
            "latitude"=>["required",new doubleRule()],
            "Longitude"=>["required",new doubleRule()],
        ]);
        $this->request = $request;

        $order= Order::find($request->orderId);
        ArrivedDriverAlert::dispatch($order,"ArrivedDriverAlertJob-{$order->id}-order_id")->delay(now()->addSeconds(260));
        $fromLocation= $order->locations->where('type','from')->first();

        if ($this->caldistance($fromLocation->latitude,$fromLocation->longitude,$request->latitude,$request->Longitude)){
            return  (new ApiResponse(406,__('api.ArrivedNotAllowedIfYouFarThanPickupLocation'),[]))->send();
        }

        if($this->checkDriverCanArrived() === false){
            $response = new ApiResponse(420,__('api.you cant driver this order'),[]);
        }elseif($this->checkOrderISAvailable() === false){
            $response = new ApiResponse(421,__('api.this order is not available'),[]);
        }else{
            $this->arrivedOrderByDriver();
            $order->update(["status"=>orderStatus::arrived]);
            OrderDetailsService::arrivedTime($order->id);
            new generalEvent(new OrderResource($order),[$order->user_id],'CapitanArrived',__("api.your request has been accepted successfully by driver"));
            $response = new ApiResponse(200,__('api.your request has been arrived driver successfully'),[]);
        }
        return $response->send();
    }

    private function checkDriverCanArrived() :bool
    {
        return  OrderSentToDriver::where('user_id',auth()->user()->id)
                ->where('order_id',$this->request->orderId)
                ->where('status',orderStatus::accept)
                ->count() > 0;
    }

    private function checkOrderISAvailable() :bool
    {
        return  OrderSentToDriver::where('order_id',$this->request->orderId)
                ->where('status',orderStatus::arrived)
                ->count() < 1;
    }

    private function arrivedOrderByDriver()
    {
        return  OrderSentToDriver::where('order_id',$this->request->orderId)
            ->where('status',orderStatus::accept)
            ->where('user_id',auth()->user()->id)
            ->update(['status'=>orderStatus::arrived]);
    }


    private function caldistance($p1_lat,$p1_lang,$p2_lat,$p2_lang){

        // Calculate the distance between the points
        $distance = sqrt(pow($p2_lang - $p1_lang, 2) + pow($p2_lat - $p1_lat, 2));

        // Round the distance to a desired precision
        $roundedDistance = round($distance, 2);

        if ($roundedDistance >= 0 && $roundedDistance < 50)
            return false;
        return true;
    }

}

<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Http\Resources\locationResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Response\ApiResponse;
use App\Rules\doubleRule;
use App\services\order\OrderDetailsService;
use App\services\order\priceCalculation;
use App\services\orderCycle\offerCycle;
use App\services\orderCycle\sendBlockCycle;
use App\services\orderCycle\sendSerialCycle;
use App\services\walletService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class makeHoursOrderController extends Controller
{

    public function index(Request $request)
    {
       $this->validted($request);

        if ($request->payment_type_id == 1 &&$this->checkWalletAmount()){
            $response=  new ApiResponse(406,__('api.yourWalletBalanceIsLow'),[]);
            return $response->send();
        }

        $order=$this->createOrder($request);

        $this->CreateLocation($request->fromLocation,$order,'from');
        foreach ($request->toLocation as $location){
            $this->CreateLocation($location,$order,'to');
        }


        OrderDetailsService::Create($order->id);
        priceCalculation::startCalHours($order,$request->ride_type_id);


        $res = $this->sendToDriver($order->shipment_type_id,$order,$request->ride_type_id);

        if ($res){
            $response=  new ApiResponse(200,__('api.new_order'),[
                'order'=>new OrderResource($order)
            ]);
            return $response->send();
        }else{
            $order->delete();
            $response=  new ApiResponse(200,__('api.no driver founded please try later'),[]);
            return $response->send();
        }
    }

    public function createOrder($request) :Order
    {
        return   createDB(Order::class,[
            'user_id'=>auth()->user()->id,
            'status'=>"waiting",
            'shipment_type_id'=>$request->shipment_type_id,
            'hours'=>$request->hours,
            "created_at"=>now(),
            'appKey'=>env(request()->header('appKey')),
            'payment_type_id'=>$request->payment_type_id
        ]);

    }

    public function CreateLocation(array $locationRequest, Order $order,string $type='') : locationResource
    {
        $location = $order->locations()->create($locationRequest+['type'=>$type]);
        return new locationResource($location);
    }

    public function sendToDriver($shipmentType,$order,$ride_Type){

        return sendBlockCycle::SentToDriver($order,$ride_Type);
    }

    public function validted($request){
        $request->validate([
            'locationId'=>'required_if:fromLocation,',
            'fromLocation.longitude'=>['required_if:locationId,','required','numeric',new doubleRule],
            'fromLocation.latitude'=>['required','numeric',new doubleRule],
            'toLocation.*.longitude'=>['required','numeric',new doubleRule],
            'toLocation.*.latitude'=>['required','numeric',new doubleRule],
            'shipment_type_id'=>"required|exists:shipment_type,id",
            'payment_type_id'=>"required|integer|between:1,3",
            'ride_type_id'=>"integer",
            'hours'=>"required|integer|min:60",
            'card_id'=>["required_if:payment_type_id,2","nullable","integer"]
        ]);
    }

    private function checkWalletAmount()
    {
        $userWalletAmount = (new walletService())->GetWalletBalance(auth()->id()) ;
        if ($userWalletAmount > 0)
            return false;
        return true;
    }
}

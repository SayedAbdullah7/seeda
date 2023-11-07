<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Enums\orderStatus;
use App\Http\Controllers\Controller;
use App\services\order\OrderDetailsService;
use App\services\order\priceCalculation;
use App\services\orderCycle\offerCycle;
use App\services\orderCycle\sendBlockCycle;
use App\services\orderCycle\sendSerialCycle;
use App\services\walletService;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use App\Http\Resources\locationResource;
use App\Response\ApiResponse;

class makeOrderController  extends Controller
{
    public function index(Request $request)
    {
//        $lastOrder = Order::where("user_id",auth()->id())->whereIn("status",[orderStatus::accept,orderStatus::waiting,orderStatus::start])->get();
//        if (count($lastOrder) > 0){
//            $response=  new ApiResponse(406,__('api.finishActiveOrderBeforeOrderNewOne'),[]);
//            return $response->send();
//        }

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
        if (in_array($order->shipment_type_id,[1,2])){
            priceCalculation::startCal($order,$request->ride_type_id);
        }

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
        switch ($shipmentType){
            case 1:
                return sendBlockCycle::SentToDriver($order,$ride_Type);
                break;
            case 2:
                return sendSerialCycle::SentToDriver($order,$ride_Type);
                break;
            case 3:
                return offerCycle::SentToDriver($order,$ride_Type);
                break;
        }
        return false;
    }

    private function checkWalletAmount()
    {
        $userWalletAmount = (new walletService())->GetWalletBalance(auth()->id()) ;
        if ($userWalletAmount > 0)
            return false;
        return true;
    }
}

<?php

namespace App\services\order;

use App\Enums\orderStatus;
use App\Http\Resources\locationResource;
use App\Integrations\payment\factory\Paymob;
use App\Models\Card;
use App\Models\Location;
use App\Models\Medias;
use App\Models\Order;
use App\Models\Vehicles;
use App\Response\ApiResponse;
use App\services\Gps\ScooterGefonceLocationPoint;
use App\services\Gps\sdnService;
use App\services\walletService;
use Modules\Sonic\Transformers\OrderResource;

class EndOrderByAdmin
{
    public function end($order_id){

        $order  = Order::with("user")->where("id",$order_id)->first();
        $user   = $order->user;
        if ($order->status == orderStatus::start){
            $vehicle = Vehicles::where("id",$order->scooter_id)->first();
            $check = sdnService::lockByAdmin($vehicle);
            if ($check){
                $toLocation = $this->UpdateVehiclesLocation($vehicle);
                $order->update(["status"=>orderStatus::end]);
                $this->CreateLocation($toLocation,$order,'to');

                OrderDetailsService::endTime($order->id);
                $price = priceCalculation::scooterEndCalculationByAdmin($order);

                $vehicle->update(["is_open"=>false]);
                //deposit from wallet
                if($order->payment_type_id == 1){
                    (new walletService())->depositFromUser($price,$user->id);
                }else if($order->payment_type_id == 2){
                    $card = Card::find($order->card_id);
                    return (new Paymob())->payWithToken($card,$user,$price,"card","order",$order->id,0,1);
                }
                return (new ApiResponse(200,__("api.scooterOrderEndedSuccessfully"),["data"=>new OrderResource($order)]))->send();
            }
            return (new ApiResponse(406,__("api.TryEndAgainSomeErrorHappened"),[]))->send();
        }
        return (new ApiResponse(406,__("api.OrderEndedBefore"),[]))->send();
    }

    public function CreateLocation(array $locationRequest, Order $order,string $type='') : locationResource
    {
        $location = $order->locations()->create($locationRequest+['type'=>$type]);
        return new locationResource($location);
    }
    public function UpdateVehiclesLocation($vehicle){
        $location = sdnService::getVehicleByIdInAdmin($vehicle);

        Location::where("locationable_id",$vehicle->id)->where("locationable_type",Vehicles::class)->update([
            "latitude"=>$location["data"][0]["location_lat"],
            "longitude"=>$location["data"][0]["location_lng"],
        ]);
        return [
            "latitude"=>$location["data"][0]["location_lat"],
            "longitude"=>$location["data"][0]["location_lng"],
        ];
    }

    public function checkUploadEndImage($order){
        $is_uploaded_image = Medias::where("mediaable_type",Order::class)->where("mediaable_id",$order->id)->where("type","orderEnd")->first();
        if ($is_uploaded_image)
            return true;
        return false;
    }
}

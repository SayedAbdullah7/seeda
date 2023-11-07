<?php

namespace App\Jobs;

use App\Enums\orderStatus;
use App\Http\Resources\locationResource;
use App\Integrations\payment\factory\Paymob;
use App\Models\Card;
use App\Models\Location;
use App\Models\Order;
use App\Models\User;
use App\Models\Vehicles;
use App\Response\ApiResponse;
use App\services\Gps\ScooterGefonceLocationPoint;
use App\services\Gps\sdnService;
use App\services\order\OrderDetailsService;
use App\services\order\priceCalculation;
use App\services\walletService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Modules\Sonic\Transformers\OrderResource;

class EndScooterOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public $order,public string $queueName)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order  = $this->order;
        $user = User::find($order->user_id);
        $vehicle = Vehicles::where("id",$order->scooter_id)->first();
        $check = sdnService::lockByAdmin($vehicle);

        if ($check["status"]){
            $toLocation = $this->UpdateVehiclesLocation($vehicle,$order->id);
            $order->update(["status"=>orderStatus::end]);
            $this->CreateLocation($toLocation,$order,'to');

            OrderDetailsService::endTime($order->id);
            $price = priceCalculation::scooterEndCalculationByAdmin($order);

            Cache::put("vh".$order->scooter_id,now());
            $vehicle->update(["is_open"=>false]);
            //deposit from wallet
            $walletService = new walletService();
            if($order->payment_type_id == 1){
                $walletService->depositFromUser($price,$user->id);
            }else if($order->payment_type_id == 2){
                $walletBalance = $walletService->GetWalletBalance($user->id);
                if ($walletBalance < 0 ){
                    $walletService->chargeAnthoerUser(($walletBalance * -1),$user->id);
                    $price += ($walletBalance * -1);
                }
                Cache::put("order_command_".$order->id,$check["command"]);
                Cache::put("order_vehicle_imme_".$order->id,$vehicle->imme);
                $card = Card::find($order->card_id);
                 (new Paymob())->payWithToken($card,$user,$price,"card","order",$order->id,0,1);
            }
        }
    }
    public function UpdateVehiclesLocation($vehicle,$order_id){
        $location = sdnService::getVehicleByIdInAdmin($vehicle);

        $distance = $location["data"][0]["last_location"]["sdn_mileage"] - $vehicle->mileage;

        OrderDetailsService::price($order_id,["distance"=>$distance]);
        $vehicle->update(["mileage"=>$location["data"][0]["last_location"]["sdn_mileage"]]);
        Location::where("locationable_id",$vehicle->id)->where("locationable_type",Vehicles::class)->update([
            "latitude"=>$location["data"][0]["location_lat"],
            "longitude"=>$location["data"][0]["location_lng"],
        ]);
        return [
            "latitude"=>$location["data"][0]["location_lat"],
            "longitude"=>$location["data"][0]["location_lng"],
        ];
    }
    public function CreateLocation(array $locationRequest, Order $order,string $type='') : locationResource
    {
        $location = $order->locations()->create($locationRequest+['type'=>$type]);
        return new locationResource($location);
    }
}

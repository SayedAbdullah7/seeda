<?php

namespace App\services\order;

use App\Models\Location;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\PriceConfig;
use App\Models\Subscriber;
use App\Models\Subscription;
use App\Models\Tax;
use App\services\Map\MapService;
use App\services\PromoCodeService;
use App\services\walletService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class priceCalculation
{
    /**
     * @requires Order object
     *
    */
    public static function endCalculation($order){
        $orderDetails = OrderDetails::where("order_id",$order->id)->first();
        $fromLocation= $order->locations->where('type','from')->first();
        $toLocation= Location::where("locationable_type",Order::class)->where("locationable_id",$order->id)->where('type','to')->get();
//        dd($toLocation);
        $totalDuration = (new Carbon($orderDetails->end_time))->diffInSeconds(new Carbon($orderDetails->start_time));
        $totalDurationWaiting = (new Carbon($orderDetails->start_time))->diffInSeconds(new Carbon($orderDetails->arrived_time));
        $data["time_taken"] = gmdate('H:i:s', $totalDuration);
        $data["waiting_time"] = gmdate('H:i:s', $totalDurationWaiting);

        $distance = 0;
        $duration = 0;
        $traffic_duration = 0;
        //distance calculation
        for ($index=0;$index < count($toLocation);$index++){
            $previous = $index;
            $from_location = ($index == 0)?$fromLocation:$toLocation[$previous-1];
            $to_location = $toLocation[$index];
            $distanceCal =MapService::TwoPointDistanceDetails($from_location->latitude,$from_location->longitude,$to_location->latitude,$to_location->longitude);
            $distance += $distanceCal["distance"];
            $duration += $distanceCal["duration"];
            $traffic_duration += $distanceCal["traffic_duration"];
        }
        $data["distance"] =$distance;

        // price calculation
        $price=PriceConfig::where("appKey",appKey())->where("shipment_type_id",$order->shipment_type_id)
            ->where("ride_types_id",$order->driver->user->Vehicles->ride_types_id)->first();

        $data["price"]=self::calculation($price,[
            "distance"=>$distance,
            "duration"=>$duration,
            "traffic_duration"=>$traffic_duration
        ]);

        //discount calculation
        $priceAfterDiscount = PromoCodeService::ApplyPromoCodeEnd($data["price"],$order->user_id);
        if ($priceAfterDiscount == 0)
            $discount = 0;
        else
            $discount = $data["price"] - $priceAfterDiscount;

        // tax calculation
        $data +=self::tax($data["price"],$order);
        $data["price"]=self::calculation($price,[
            "distance"=>$distance,
            "duration"=>$duration,
            "traffic_duration"=>$traffic_duration
        ]);
        $data["userPrice"] = $data["userPrice"] -$discount;
        $data["discount"] = $discount;
        //update details
        $orderDetails->update($data);

        //adding driver money
        DriverWalletServices::endOrderChargeWallet($order,$data);
        return $data["userPrice"];
    }
    public static function startCal($order,$ride_types_id){
        $orderDetails = OrderDetails::where("order_id",$order->id)->first();
        $fromLocation= $order->locations->where('type','from')->first();
        $toLocation= $order->locations->where('type','to');
//            $distance =MapService::TwoPointDistanceDetails($fromLocation->latitude,$fromLocation->longitude,$toLocation->latitude,$toLocation->longitude);
        $price=PriceConfig::where("appKey",appKey())->where("shipment_type_id",$order->shipment_type_id)
            ->where("ride_types_id",$ride_types_id)->first();

        $distance = 0;
        $duration = 0;
        $traffic_duration = 0;
        //distance calculation
        for ($index=1;$index < count($toLocation);$index++){
            $previous = $index;
            $from_location = ($index == 1)?$fromLocation:$toLocation[$previous-1];
            $to_location = $toLocation[$index];
            $distanceCal =MapService::TwoPointDistanceDetails($from_location->latitude,$from_location->longitude,$to_location->latitude,$to_location->longitude);
            $distance += $distanceCal["distance"];
            $duration += $distanceCal["duration"];
            $traffic_duration += $distanceCal["traffic_duration"];
        }
        $data["distance"] =$distance;

        $data["price"]=self::calculation($price,[
            "distance"=>$distance,
            "duration"=>$duration,
            "traffic_duration"=>$traffic_duration
        ]);

        $data +=self::startTax($data["price"],$ride_types_id);
        $orderDetails->update($data);

        return $data["price"];
    }

    public static function calculation($price,$distance){
        $total_price = $price->km_price * ($distance["distance"]/1000 );
        $total_price += $price->duration * ($distance["distance"]/1000 );
        if (($distance["traffic_duration"] - $distance["duration"])>0){
            $total_price += $price->traffic_jam_price * ($distance["duration"]/1000 );
        }
        $total_price += $price->fixed_fees;
        return $total_price;
    }

    static function kmPriceCal(){

    }

    static function timePriceCal(){

    }

     static function tax($price, $order)
    {
        $userTax=0;
        $captainTax=0;
        $taxes = Tax::where("appKey",appKey())->where("country_id",1)->get();
        foreach ($taxes as $tax){
            if ($tax->type == 1){
                if ($tax->type_id){
                    if ($tax->type_id == auth()->id()){
                        $userTax += self::calTax($price,$tax->tax_type,$tax->value);
                    }
                }else{
                    $userTax += self::calTax($price,$tax->tax_type,$tax->value);
                }
            }elseif ($tax->type == 2){

                $Subscriber = Subscriber::with("Subscription")->where("user_id",auth()->id())->first();
                if ($Subscriber){
                    $captainTax += self::calTax($price,$Subscriber->Subscription->commission_type,$Subscriber->Subscription->newCommission);
                }else{
                    if ($tax->type_id){
                        if ($tax->type_id == auth()->id()){
                            $captainTax += self::calTax($price,$tax->tax_type,$tax->value);
                        }
                    }else{
                        $captainTax += self::calTax($price,$tax->tax_type,$tax->value);
                    }
                }
            }elseif($tax->type == 3){
                if ($tax->type_id == $order->driver->user->Vehicles->ride_types_id){
                    $userTax += self::calTax($price,$tax->tax_type,$tax->value);
                }
            }
        }
        return [
            "captainTax" => $captainTax,
            "userTax"    =>$userTax,
            "captainPrice"=>($price-$captainTax),
            "userPrice"=>($price + $userTax),
        ];
    }

     public static function startTax($price, $ride_types_id)
    {
        $userTax=0;
        $captainTax=0;
        $taxes = Tax::where("appKey",appKey())->where("country_id",1)->get();
        foreach ($taxes as $tax){
            if ($tax->type == 1){ // tax on user
                if ($tax->type_id){
                    if ($tax->type_id == auth()->id()){
                        $userTax += self::calTax($price,$tax->tax_type,$tax->value);
                    }
                }else{
                    $userTax += self::calTax($price,$tax->tax_type,$tax->value);
                }
            }elseif ($tax->type == 2){// tax on captain
                $Subscriber = Subscriber::with("Subscription")->where("user_id",auth()->id())->first();
                if ($Subscriber){
                    $captainTax += self::calTax($price,$tax->tax_type,$Subscriber->Subscription->newCommission);
                }else{
                    if ($tax->type_id){
                        if ($tax->type_id == auth()->id()){
                            $captainTax += self::calTax($price,$tax->tax_type,$tax->value);
                        }
                    }else{
                        $captainTax += self::calTax($price,$tax->tax_type,$tax->value);
                    }
                }

            }elseif($tax->type == 3){ // tax on order
                if ($tax->type_id == $ride_types_id){
                    $userTax += self::calTax($price,$tax->tax_type,$tax->value);
                }
            }
        }
        return [
            "captainTax" => $captainTax,
            "userTax"    =>$userTax,
            "captainPrice"=>($price-$captainTax),
            "userPrice"=>($price + $userTax),
        ];
    }

     private static function calTax($price , $type , $value){
        if ($type == 1 ){
            return $value;
        }else{
            return ($price/100) * $value ;
        }
     }

	public static function endOfferCalculation($order){
        $orderDetails = OrderDetails::where("order_id",$order->id)->first();
        $fromLocation= $order->locations->where('type','from')->first();
        $toLocation= $order->locations->where('type','to')->first();
        $totalDuration = (new Carbon($orderDetails->end_time))->diffInSeconds(new Carbon($orderDetails->start_time));
        $totalDurationWaiting = (new Carbon($orderDetails->start_time))->diffInSeconds(new Carbon($orderDetails->arrived_time));
        $data["time_taken"] = gmdate('H:i:s', $totalDuration);
        $data["waiting_time"] = gmdate('H:i:s', $totalDurationWaiting);

        //distance calculation
        $distance =MapService::TwoPointDistanceDetails($fromLocation->latitude,$fromLocation->longitude,$toLocation->latitude,$toLocation->longitude);
        $data["distance"] =$distance["distance"];

        // price calculation
        $price=PriceConfig::where("appKey",appKey())->where("shipment_type_id",$order->shipment_type_id)
            ->where("ride_types_id",$order->driver->user->Vehicles->ride_types_id)->first();

        $data["price"]=$orderDetails["price"];

        //discount calculation
        $priceAfterDiscount = PromoCodeService::ApplyPromoCodeEnd($data["price"],$order->user_id);
        if ($priceAfterDiscount == 0)
            $discount = 0;
        else
            $discount = $data["price"] - $priceAfterDiscount;

        // tax calculation
        $data +=self::tax($data["price"],$order);
        $data["price"]=$orderDetails["price"];
        $data["userPrice"] = $data["userPrice"] -$discount;
        $data["discount"] = $discount;
        //update details
        $orderDetails->update($data);

        //adding driver money
        DriverWalletServices::endOrderChargeWallet($order,$data);

        return $data["userPrice"];
    }

    public static function scooterEndCalculation($order){
        $orderDetails = OrderDetails::where("order_id",$order->id)->first();

        $fromLocation= $order->locations->where('type','from')->first();
        $toLocation= $order->locations->where('type','to')->first();

        $totalDuration = (new Carbon($orderDetails->end_time))->diffInSeconds(new Carbon($orderDetails->start_time));
        $totalDurationWaiting = (new Carbon($orderDetails->start_time))->diffInSeconds(new Carbon($orderDetails->accept_time));

        $data["time_taken"] = gmdate('H:i:s', $totalDuration);
        $data["waiting_time"] = gmdate('H:i:s', $totalDurationWaiting);

        //distance calculation
        //$distance =MapService::TwoPointDistanceDetails($fromLocation->latitude,$fromLocation->longitude,$toLocation->latitude,$toLocation->longitude);

        //$data["distance"] =$distance["distance"];
        // price calculation
        $price=PriceConfig::where("appKey",appKey())->where("shipment_type_id",$order->shipment_type_id)
            ->where("ride_types_id",$order->Vehicles->ride_types_id)->first();

        $data["userPrice"]=self::ScooterCalculation($price, $data["time_taken"]);

        //discount calculation
        $priceAfterDiscount = PromoCodeService::ApplyPromoCodeEnd($data["userPrice"],$order->user_id);
        if ($priceAfterDiscount == 0)
            $discount = 0;
        else
            $discount = $data["userPrice"] - $priceAfterDiscount;

        // tax calculation
        $data +=self::tax($data["userPrice"],$order);
        $data["price"] = $data["userPrice"];
        $data["userPrice"] = $data["userPrice"] -$discount;
        $data["discount"]  = $discount;


        //update details
        $orderDetails->update($data);
        if ($order->payment_type_id == 2)
            return $data["userPrice"]-$price->fixed_fees;
        return $data["userPrice"];
    }

    public static function scooterEndCalculationByAdmin($order){
        $orderDetails = OrderDetails::where("order_id",$order->id)->first();

        $fromLocation= $order->locations->where('type','from')->first();
        $toLocation= $order->locations->where('type','to')->first();

        $totalDuration = (new Carbon($orderDetails->end_time))->diffInSeconds(new Carbon($orderDetails->start_time));
        $totalDurationWaiting = (new Carbon($orderDetails->start_time))->diffInSeconds(new Carbon($orderDetails->accept_time));

        $data["time_taken"] = gmdate('H:i:s', $totalDuration);
        $data["waiting_time"] = gmdate('H:i:s', $totalDurationWaiting);

        //distance calculation
        //$distance =MapService::TwoPointDistanceDetails($fromLocation->latitude,$fromLocation->longitude,$toLocation->latitude,$toLocation->longitude);

        //$data["distance"] =$distance["distance"];
        // price calculation
        $price=PriceConfig::where("appKey","527")->where("shipment_type_id",$order->shipment_type_id)
            ->where("ride_types_id",$order->Vehicles->ride_types_id)->first();

        $data["userPrice"]=self::ScooterCalculation($price, $data["time_taken"]);

        //discount calculation
        $priceAfterDiscount = PromoCodeService::ApplyPromoCodeEnd($data["userPrice"],$order->user_id);
        if ($priceAfterDiscount == 0)
            $discount = 0;
        else
            $discount = $data["userPrice"] - $priceAfterDiscount;

        // tax calculation
        $data +=self::tax($data["userPrice"],$order);
        $data["price"]=$data["userPrice"];
        $data["userPrice"] = $data["userPrice"] -$discount;
        $data["discount"] = $discount;


        //update details
        $orderDetails->update($data);

        return $data["userPrice"];
    }

    static function ScooterCalculation($price, $time){

      	$startTime= explode(":",$time);//00:01:31

        $sTime = ((int)$startTime[0] * 60) + (int)$startTime[1];
		$x=  (((int)$startTime[2] > 0))?1:0;
        $sTime = $sTime + $x;

		//dd($sTime);
        return ($price->move_minute_price * $sTime) + $price->fixed_fees;
    }

    public static function startCalHours($order,$ride_types_id){
        $orderDetails = OrderDetails::where("order_id",$order->id)->first();

        $price=PriceConfig::where("appKey",appKey())->where("shipment_type_id",$order->shipment_type_id)
            ->where("ride_types_id",$ride_types_id)->first();

        $data["price"]=($order->hours * $price->move_minute_price) + $price->fixed_fees;

        $data +=self::startTax($data["price"],$ride_types_id);

        $orderDetails->update($data);
        return $data["price"];
    }

       public static function EndCalHours($order){
        $orderDetails = OrderDetails::where("order_id",$order->id)->first();
        $toLocation= $order->locations->where('type','to')->first();
        $totalDuration = (new Carbon($orderDetails->end_time))->diffInSeconds(new Carbon($orderDetails->start_time));
        $totalDurationWaiting = (new Carbon($orderDetails->start_time))->diffInSeconds(new Carbon($orderDetails->arrived_time));
        $data["time_taken"] = gmdate('H:i:s', $totalDuration);
        $data["waiting_time"] = gmdate('H:i:s', $totalDurationWaiting);


        $price=PriceConfig::where("appKey",appKey())->where("shipment_type_id",$order->shipment_type_id)
            ->where("ride_types_id",$order->driver->user->Vehicles->ride_types_id)->first();

        $data["price"]=(($totalDuration/60) * $price->move_minute_price) + $price->fixed_fees;

        $data +=self::tax($data["price"],$order->driver->user->Vehicles->ride_types_id);

        $orderDetails->update($data);

        //adding driver money
        DriverWalletServices::endOrderChargeWallet($order,$data);


        return $data["userPrice"];
    }
}

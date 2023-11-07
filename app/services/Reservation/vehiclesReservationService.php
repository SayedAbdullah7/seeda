<?php

namespace App\services\Reservation;

use App\Integrations\payment\factory\Paymob;
use App\Jobs\ReservationJob;
use App\Models\Card;
use App\Models\Configration;
use App\Models\VehicleReservation;
use App\Models\Vehicles;
use App\services\walletService;

class vehiclesReservationService
{

    public static function reserve($vehicle_id ,int $time,$payment_id,$card_id){
        if ($payment_id == 1){
            if (self::checkWallet($time)){
                Vehicles::find($vehicle_id)->update(["is_open"=>1]);
                return VehicleReservation::create([
                    "user_id"=>auth()->id(),
                    "vehicles_id"=>$vehicle_id,
                    "time"=>$time,
                    "active"=>1,
                    "from"=>now(),
                    "to"=>now()->addMinutes($time),
                    "appKey"=>appKey(),
                ]);
            }
        }elseif ($payment_id == 2){
            $reservationFees =Configration::where("appKey",appKey())->where("key","reservation_fees")->first();
            $price = $time * $reservationFees->value["value"];
            $card = Card::find($card_id);
            return (new Paymob())->payWithToken($card,auth()->user(),$price,"card","reservation",$vehicle_id,0,0,1,$time);
        }

        return  null;
    }

    private static function checkWallet(int $time)
    {
        $wallet = new walletService();
        $balance = $wallet->GetWalletBalance(auth()->id());
        $reservationFees =Configration::where("appKey",appKey())->where("key","reservation_fees")->first();
        $price = $time * $reservationFees->value["value"];
        if ($price < $balance){
            $wallet->depositFromUser($price , auth()->id());
            return true;
        }
        return false;
    }

}

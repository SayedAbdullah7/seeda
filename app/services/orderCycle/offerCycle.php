<?php

namespace App\services\orderCycle;

use App\Enums\orderStatus;
use App\Events\generalEvent;
use App\Http\Resources\OrderResource;
use App\Models\Configration;
use App\Models\OrderSentToDriver as model;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class offerCycle
{
    public static function SentToDriver( $order,$ride_type_id)
    {
        $data=[];
        $orderId = $order->id;
      $rejectedDrivers1= model::where('order_id',$orderId)
            ->where('status',orderStatus::cancel)
            ->pluck('user_id')->toArray();

        $rejectedDrivers2 = model::whereIn("status",[orderStatus::accept,orderStatus::start])->pluck('user_id')->toArray();
        $rejectedDrivers = $rejectedDrivers1->merge($rejectedDrivers2);

        $resp = true;

        return self::sendOfferToAll($rejectedDrivers,$order);
    }

    static function sendOfferToAll($rejectedDrivers,$order)
    {
        $drivers= User::checkDriver()->with("Activelocations")->whereNotIn('id',$rejectedDrivers)
            ->where("appKey",appKey())->where('type','Like','%captain%')->get();

        if (count($drivers) == 0){
            return false;
        }
        $nearestDrivers = NearestDriverService::getNearestDriver($drivers,$order);
        $data = [];
        foreach($nearestDrivers as $driver)
        {
            $data[]=[
                'user_id'=>$driver,
                "status"=>orderStatus::waiting,
                'order_id'=>$order->id,
                'created_at'=>now()
            ];
        }

        if ($data == []){
            return false;
        }

        model::insert($data);
//        self::notification($drivers);
        new generalEvent(new OrderResource($order),$nearestDrivers,'newOfferOrder',__("api.you have new order"));
        return true;
    }



}

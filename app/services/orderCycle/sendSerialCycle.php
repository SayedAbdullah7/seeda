<?php

namespace App\services\orderCycle;

use App\Enums\orderStatus;
use App\Events\generalEvent;
use App\Http\Resources\OrderResource;
use App\Jobs\AutoNextDriverJob;
use App\Models\OrderSentToDriver as model;
use App\Models\User;
use App\services\Map\MapService;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class sendSerialCycle
{
    public static function SentToDriver( $order,$ride_type_id)
    {
        $data=[];
        $orderId = $order->id;
		$rejectedDrivers1= model::where('order_id',$orderId)
            ->where('status',orderStatus::cancel)
            ->pluck('user_id')->toArray();

        $rejectedDrivers2 = model::whereIn("status",[orderStatus::accept,orderStatus::start])->pluck('user_id')->toArray();
        $rejectedDrivers = array_merge($rejectedDrivers1,$rejectedDrivers2);//$rejectedDrivers1->merge($rejectedDrivers2);

        return self::sendSerail($rejectedDrivers,$order,$ride_type_id);
    }

    public static function sendNext($order){
        $orderId = $order->id;
        $driver =  model::where('order_id',$orderId)
            ->where('status',orderStatus::waiting)
            ->pluck('user_id');
        if (count($driver) == 0){
            new generalEvent($order,[$order->user_id],'NoDriverAcceptedOrFounded',__("api.order has been cancelled automatic "));
        }else{
            AutoNextDriverJob::dispatch($order,"AutoNextDriverJob-{$order->id}-order_id")->delay(now()->addSeconds(15));
            new generalEvent($order,[$driver[0]],'newOrder',__("api.you have new order"));
        }
    }

    static function sendSerail($rejectedDrivers,$order,$ride_type_id){
        \Illuminate\Support\Facades\Storage::put('data1.json', json_encode([''=>'sendSerail']));

        $drivers= User::checkDriver()->with("Activelocations")->where("appKey",appKey())
            ->whereNotIn('id',$rejectedDrivers)->where('type','Like','%captain%')
            ->whereHas('Vehicles.rideType', function (Builder $query) use ($ride_type_id) {
                return $query->where('ride_types_id', $ride_type_id);
            })->get();
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

        AutoNextDriverJob::dispatch(new OrderResource($order),"AutoNextDriverJob-{$order->id}-order_id")->delay(now()->addSeconds(5));
        $count = count($nearestDrivers);
        $max = min($count, 3);
        for ($x = 0; $x <= ($max-1); $x++) {
            new generalEvent(new OrderResource($order), [$nearestDrivers[$x]], 'newOrder', __("api.you have new order"));
        }
        return true;
    }


}

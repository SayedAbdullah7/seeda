<?php

namespace App\services\order;

use App\Models\OrderDetails;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class OrderDetailsService
{
    public static function Create($orderId,$order_details=null){
        OrderDetails::create([
            "order_id"=>$orderId,
            "order_details"=>$order_details
        ]);
    }

    public static function updateLinPoints($orderId,$lat_line,$long_line){
        OrderDetails::where("order_id",$orderId)->update([
                "lat_line"=>$lat_line,
                "long_line"=>$long_line
        ]);
    }

    public static function updateLinPointsWithOld($orderId,$lat,$lng){
        $orderDetails= OrderDetails::where("order_id",$orderId)->first();

        if ($orderDetails->lat_line != null){
            $newLat = $orderDetails->lat_line;
            $newLat[]= $lat;
        }else{
            $newLat= [$lat];
        }

        if ($orderDetails->long_line != null){
            $newLng = $orderDetails->long_line;
            $newLng[]=$lng;
        }else{
            $newLng=[$lng];
        }

        $orderDetails->update([
            "lat_line"=>$newLat,
            "long_line"=>$newLng
        ]);
    }

    public static function acceptTime($orderId){
        $orderDetails = OrderDetails::where("order_id",$orderId)->first();
        $orderDetails->update(["accept_time"=>now()]);
    }

    public static function arrivedTime($orderId){
        $orderDetails = OrderDetails::where("order_id",$orderId)->first();
        $orderDetails->update(["arrived_time"=>now()]);
    }

    public static function startTime($orderId){
        $orderDetails = OrderDetails::where("order_id",$orderId)->first();
        if (auth()->check()){
            Log::error("user_id".auth()->id()."is_updateStartTime:".$orderId);
        }else{
            Log::error("Cron_is_updateStartTime:".$orderId);
        }
        $orderDetails->update(["start_time"=>now()]);
    }

    public static function endTime($orderId){
        $orderDetails = OrderDetails::where("order_id",$orderId)->first();
        $orderDetails->update(["end_time"=>now()]);
    }

    public static function price($orderId,$data){
        // this Data content price & distance & discount if founded & timeTaken
        $orderDetails = OrderDetails::where("order_id",$orderId)->first();
        $orderDetails->update($data);
    }

}

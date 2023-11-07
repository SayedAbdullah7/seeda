<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Enums\orderStatus;
use App\Models\DriverEaring;
use App\Models\Order;
use App\Response\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Seda\Transformers\EaringResource;

class EaringController extends Controller
{
    public function index()
    {
        $previousEaring = DriverEaring::where("user_id",auth()->id())->latest()->get();
        return (new ApiResponse(200,__("api.EaringRetrievedSuccessfully"),[
            "dailyEaring"=>$this->DailyEaring(),
            "previousEaring"=>EaringResource::collection($previousEaring),
        ]))->send();
    }

    public function DailyEaring(){
        $date = date('Y-m-d');
        $dateStr = date('D');

        $orders= Order::with("OrderDetails")->where("status",orderStatus::end)
            ->Where(function($q){
                return $q->whereHas('driver',function($q){
                    return $q->where('user_id',auth()->id());
                });
            })->Where(function($q)use ($date){
                return $q->whereHas('OrderDetails',function($q)use($date){
                    return $q->whereDate("end_time",$date);
                });
            })->get();

        $captainPrice =0;
        $AppFees =0;
        $discount =0;
        $eraing =0;
        $totalTime=0;

        foreach ($orders as $order){
            $captainPrice += $order->OrderDetails->captainPrice;
            $AppFees += $order->OrderDetails->captainTax;
            $discount += $order->OrderDetails->captainPrice;
            $eraing += $order->OrderDetails->captainPrice - $order->OrderDetails->captainTax;

            $time = $order->OrderDetails->time_taken;
            $arrTime = explode(":",$time);
            $totalTime += (((int)$arrTime[0])
                +((int)$arrTime[1]/60)+
                (((int)$arrTime[2]/60)/60));
        }


            return[
                "user_id"=>auth()->id(),
                "trips_num"=>count($orders),
                "captainPrice"=>$captainPrice,
                "captainTax"=>$AppFees,
                "earing"=>$eraing,
                "discount"=>$discount,
                "hours"=>$totalTime,
                "day"=>$date,
                "day_str"=>$dateStr,
            ];

    }
}

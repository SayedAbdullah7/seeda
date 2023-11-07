<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Enums\orderStatus;
use App\Models\Configration;
use App\Models\Order;
use App\Models\OrderSentToDriver;
use App\Response\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DriveStatisticsController extends Controller
{

    public function index()
    {
        $orders= Order::with("OrderDetails")->where("status",orderStatus::end)->whereDate("created_at",now())
            ->Where(function($q){
            return $q->whereHas('driver',function($q){
                return $q->where('user_id',auth()->user()->id)->where("status",orderStatus::end);
            });
        })->get();
        $ordersCount = OrderSentToDriver::where('user_id',auth()->id())->whereDate("created_at",now())->count();
//dd($ordersCount);
        $cancel = $this->calCancelPerecentage($ordersCount);
        $totalTime =  $this->calTimeTaken($orders);

        return (new ApiResponse(200,__("api.DriveStatistics"),[
            "totalTime" => $totalTime,
            "orderCount" =>$ordersCount ,
            "cancellationPercent" =>$cancel ,
            "level" =>"silver" ,
        ]))->send();
    }

    private function calTimeTaken($orders)
    {
        $totalTime =0;
        foreach ($orders as $order){
            $time = $order->OrderDetails->time_taken;
            $arrTime = explode(":",$time);
            $totalTime += (((int)$arrTime[0])
                +((int)$arrTime[1]/60)+
                (((int)$arrTime[2]/60)/60));
        }

        return number_format($totalTime,2);
    }

    private function calCancelPerecentage($orders){
        $ordersCanceledGet = OrderSentToDriver::where('user_id',auth()->id())->where("status",orderStatus::cancel)->whereDate("created_at",now())->get();
        $cancellation_max = Configration::where("key","cancellation_max")->where("appKey",appKey())->first();
        $ordersCanceled = $ordersCanceledGet->count() - $cancellation_max->value["value"];
        if ($ordersCanceled > 0)
            return $ordersCanceled/$orders;
        return 0;
    }


}

<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Enums\orderStatus;
use App\Models\DriverEaring;
use App\Models\Order;
use App\Response\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FrontEaringController extends Controller
{

    public function index()
    {
        return (new ApiResponse(200,__("api.FrontEaringRetrievedSuccessfully"),[
            "dailyEaring"=>$this->DailyEaring(),
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


        $eraing =0;

        foreach ($orders as $order){
            $eraing += $order->OrderDetails->captainPrice - $order->OrderDetails->captainTax;
        }


        return[
            "user_id"=>auth()->id(),
            "trips_num"=>count($orders),
            "dailyEaring"=>$eraing,
        ];

    }
}

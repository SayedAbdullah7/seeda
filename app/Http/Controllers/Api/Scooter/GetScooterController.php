<?php

namespace App\Http\Controllers\Api\Scooter;

use App\Enums\mediaTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\ScooterResource;
use App\Models\PriceConfig;
use App\Models\Vehicles;
use App\Response\ApiResponse;
use Illuminate\Http\Request;

class GetScooterController extends Controller
{
    public function index(Request $request){
        $vh = Vehicles::with(["locations","Order.medias"])->where("appKey",appKey())
            ->where("is_open",false)->where("is_closed",false)
            ->where("order_ended",1)->get();

        if (count($vh) > 0){
            $price = PriceConfig::where("appKey",appKey())
                ->where("ride_types_id",$vh[0]->ride_types_id)->first();

            return (new ApiResponse(200,__("api.VehiclesRetrievedSuccessfully"),[
                "data"=>ScooterResource::collection($vh),
                'fixed_fees'=>($price == null)?number_format(0.00,"2","."):number_format($price->fixed_fees,"2","."),
                'minute_price'=>($price == null)?number_format(0.00,"2","."):number_format($price->move_minute_price,"2","."),
            ]))->send();
        }
        return (new ApiResponse(200,__("api.allVehiclesNotAvailable"),["data"=>null,'fixed_fees'=>null,'minute_price'=>null]))->send();
    }
}

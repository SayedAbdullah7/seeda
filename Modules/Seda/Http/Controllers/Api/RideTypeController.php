<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Http\Resources\hoursRideTypeResource;
use App\Models\PriceConfig;
use App\Models\RideTypes;
use App\Response\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Seda\Transformers\SerialRideTypeResource;

class RideTypeController extends Controller
{
    public function index(Request $request)
    {
        $types = RideTypes::with("Price")->where("appKey",appKey())->get();
        $prices = PriceConfig::with("RideTypes")->where("shipment_type_id",6)->where("appKey",appKey())->get();

        return (new ApiResponse(200,"asd",[
            "serial"=>SerialRideTypeResource::collection($types),
            "offer"=>null,
            "hours"=>hoursRideTypeResource::collection($prices),
        ]))->send();
    }

}

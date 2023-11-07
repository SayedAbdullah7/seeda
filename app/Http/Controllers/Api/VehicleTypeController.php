<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RideTypes;
use App\Models\VehicleColor;
use App\Models\VehicleTypes;
use App\Response\ApiResponse;
use Illuminate\Http\Request;

class VehicleTypeController extends Controller
{
    public function getVehicleType()
    {
        $vehicleColor = VehicleColor::get();
        $getRideType = RideTypes::where("appKey",appKey())->pluck("id")->toArray();
        $vehicleType = VehicleTypes::whereIn("ride_types_id",$getRideType)->select('company')->groupBy('company')->get()->toArray();
        return ( new ApiResponse(200,__('api.registerd successfully'),[
            "vehicle_color"=>$vehicleColor,
            "vehicle_type"=>$vehicleType
        ]))->send();
    }

    public function getvehiclesCompanyTypes(Request $request)
    {
        $getRideType = RideTypes::where("appKey",appKey())->pluck("id")->toArray();
        $vehicleType = VehicleTypes::with("rideType")->whereIn("ride_types_id",$getRideType)
            ->where('company', 'like', '%'.$request->company.'%')->get();
        return ( new ApiResponse(200,__('api.registerd successfully'),[
            "vehicle_type"=>$vehicleType,
        ]))->send();
    }
}

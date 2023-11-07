<?php

namespace App\Http\Controllers\Api\geofences;

use App\Http\Controllers\Controller;
use App\Http\Resources\City\CityGofenceResource;
use App\Models\City;
use App\Response\ApiResponse;
use Illuminate\Http\Request;

class CityGeofencesController extends Controller
{
    public function index(Request $request){
        $cities = City::with("Geofence")->where("appKey",appKey())->get();

        return (new ApiResponse(200,__("api."),["cities"=>CityGofenceResource::collection($cities)]))->send();
    }
}

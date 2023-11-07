<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetNearstCarRequest;
use App\Http\Resources\GetNearestCarResource;
use App\Models\User;
use App\services\Map\MapService;
use App\services\orderCycle\NearestDriverService;
use Illuminate\Http\Request;

class GetNearstCarController extends Controller
{

    public function index(GetNearstCarRequest $request)
    {
        $data = $request->all();
        $drivers= User::checkDriver()->with("Activelocations")->where("appKey",appKey())
            ->where('type','Like','%captain%')->get();
        $nearestDrivers = NearestDriverService::getNearestDriverfromLatLong($drivers,$data);
        return GetNearestCarResource::collection($nearestDrivers);
    }

}

<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
//use App\Http\Requests\GetRideTypeRequest;
use App\Http\Resources\RideTypeResource;
use App\Models\RideTypes;
use App\Response\ApiResponse;
use App\services\Map\MapService;
use Illuminate\Http\Request;

class RideTypeController extends Controller
{
    public function index(Request $request){
        return GeneralApiFactory("RideTypeController",Request::class);
    }
}

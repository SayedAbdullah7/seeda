<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\userCarResource;
use App\Models\UserVehicles;
use App\Response\ApiResponse;
use Illuminate\Http\Request;

class UsersCarController extends Controller
{
    public function index(Request $request){
        $cars = UserVehicles::with("Vehicles.medias")->where("user_id",auth()->id())->get();
        return (new ApiResponse(200,__("api.UserVehicles"),["UserVehicles"=>userCarResource::collection($cars)]))->send();
    }
}

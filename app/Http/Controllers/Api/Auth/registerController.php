<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\registerRequest;
use App\Http\Resources\userResource;
use App\Models\UserVehicles;
use App\Models\Vehicles;
use App\Models\VehicleTypes;
use App\Response\ApiResponse;
use App\services\vehicle\vehicleServices;
use  Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;

class registerController extends Controller
{

    public function index(){
        return GeneralApiFactory("registerController",Request::class);
    }
    public function register(registerRequest $request){
        $user= auth()->user();
        if ($request->hasFile("image")){
            SaveOrUpdateMedia($user,$request->file("image"),["type"=>"image"]);
        }

        if ($request->has("driver_images")){
            $this->saveDriverImage($user,$request);
        }

        if ($request->has("vehicle")){
            $this->saveVehicle($request);
        }

        $user->update([
            'name'=>$request->name,
            'nickName'=>$request->nickName,
            'email'=>$request->email,
            'gender'=>$request->gender,
            'birth'=>$request->birth,
            'type'=>$request->userDetails['type'],
        ]);

        $token=   $user->createToken('users', ['users']);
        $user->currentAccessToken()->delete();
        return
            ( new ApiResponse(200,__('api.registerd successfully'),["token"=>$token]))
                ->send();
    }


    private function saveDriverImage($user, $request)
    {
        foreach ($request["driver_images"] as $key => $image){

            SaveMedia($user,$image,["type"=>"driverImages","Second_type"=>$key]);
        }
    }

    private function saveImage($request,$vehicles)
    {
        foreach ( $request["vehicle"]["images"] as $key => $value ){
            SaveMedia2($vehicles,Vehicles::class,$value,"Vehicles",["type"=>"VehiclesImages","Second_type"=>$key]);
        }
    }

    private function saveVehicle($request){
        $data = $request->get("vehicle");
        $vehicleType = VehicleTypes::find($data["vehicle_types_id"]);
        $data["ride_types_id"] =$vehicleType->ride_types_id;
        $data["user_id"] = auth()->id();
        $data["owner_id"] = auth()->id();
        $data["appKey"] = appKey();
        $vehicles = (new vehicleServices())->createFromUser($data,$request["vehicle"]["images"]);
        UserVehicles::create([
            "user_id"=>auth()->id(),
            "vehicles_id"=>$vehicles->id
        ]);
    }
}

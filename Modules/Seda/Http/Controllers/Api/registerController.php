<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\userResource;
use App\Models\Location;
use App\Models\Massages;
use App\Models\User;
use App\Models\UserRooms;
use App\Models\UserTree;
use App\Models\UserVehicles;
use App\Models\Vehicles;
use App\Models\VehicleTypes;
use App\Rules\doubleRule;
use App\services\mediaServices;
use App\services\vehicle\vehicleServices;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Response\ApiResponse;

class registerController extends Controller
{
    public function index(Request $request){
        $user= auth()->user();
        $this->valid($request);
        if ($request->hasFile("image")){
            SaveOrUpdateMedia($user,$request->file("image"),["type"=>"image"]);
        }

        if ($request->has("code")){
            $this->assegnUserTree($request->code);
        }

        if ($request->has("location")) {
            $this->saveLocation($request->location);
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
            'code'=>Str::random(10),
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
        $vehicles = (new vehicleServices())->createFromUserSeda($data);
        UserVehicles::create([
            "user_id"=>auth()->id(),
            "vehicles_id"=>$vehicles->id
        ]);
    }

    private function valid($req){

        if ($req->has("social") && $req->social){
            $req->validate([
                'driver_images'=>"required_if:userDetails.type,==,captain|array",
                'gender'=>"string",
                'vehicle'=>"required_if:userDetails.type,==,captain|array",
                'vehicle.vehicle_types_id'=>"required_if:userDetails.type,==,captain|integer",
                'vehicle.Vehicle_color_id'=>"required_if:userDetails.type,==,captain|integer|exists:vehicle_colors,id",
                'vehicle.car_number'=>"required_if:userDetails.type,==,captain|string",
                'vehicle.purchase_year'=>"required_if:userDetails.type,==,captain|string",
                'vehicle.images'=>"required_if:userDetails.type,==,captain|array",
                'vehicle.vehicles_color'=>"string",
                'location.latitude'=>[new doubleRule()],
                'location.longitude'=>[new doubleRule()],
                'location.address'=>"string",
                'driver.*'=>"file"
            ]);
        }else{
            $req->validate([
                'image'=>'nullable',
                'name'=>'required',
                'gender'=>"string",
                'nickName'=>'nullable',
                'birth'=>'nullable',
                'email'=>'nullable|'.Rule::unique("users","email")->where("appKey",appKey()),
                //'driver_images'=>"required_if:userDetails.type,==,captain|array",
                'vehicle'=>"required_if:userDetails.type,==,captain|array",
                'vehicle.vehicle_types_id'=>"required_if:userDetails.type,==,captain|integer",
                'vehicle.Vehicle_color_id'=>"required_if:userDetails.type,==,captain|integer|exists:vehicle_colors,id",
                'vehicle.car_number'=>"required_if:userDetails.type,==,captain|string",
                'vehicle.vehicles_color'=>"string",
                'vehicle.purchase_year'=>"required_if:userDetails.type,==,captain|string",
                'location.latitude'=>[new doubleRule()],
                'location.longitude'=>[new doubleRule()],
                'location.address'=>"string",
                //'vehicle.images'=>"required_if:userDetails.type,==,captain|array",
                //'driver.*'=>"file"
            ]);
        }
    }

    private function saveLocation( $location)
    {
        Location::create([
            "locationable_type"=>User::class,
            "locationable_id"=>auth()->id(),
            "latitude"=>$location["latitude"],
            "longitude"=>$location["longitude"],
            "address"=>$location["address"],
            "type"=>"home",
        ]);
    }

    private function assegnUserTree($code)
    {
         $user = User::where("code",$code)->first();
         UserTree::create([
             "parent_id"=>$user->id,
             "child_id"=>auth()->id(),
             "appKey"=>appKey(),
         ]);
    }
}

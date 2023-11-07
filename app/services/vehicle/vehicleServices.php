<?php

namespace App\services\vehicle;

use App\Models\VehicleLogs;
use App\Models\Vehicles;
use App\services\mediaServices;

class vehicleServices
{
    /*
     * $req =>[ this is a request needed
     *      "Vehicle_color_id"=>id,
     *      "purchase_year"=>string,
     *      "car_number"=>string,
     *      "user_id"=>num ,
     *      "admin_id"=>num,
     *      "is_approve"=>tiny int
     *      "is_active"=>tiny int
     *      "$images"=>array,
     * */

    public function create($req){
        $Vehicles =  Vehicles::create($req);
        $this->updateImages($req["images"],$Vehicles);
        $this->VehiclesLogs($req->user_id,$req->admin_id,$Vehicles->id,"created");
        return $Vehicles;
    }
    public function createFromUser($req, $images){
        $Vehicles =  Vehicles::create($req);
        $this->updateImages($images,$Vehicles);
        $this->VehiclesLogs($req["user_id"],null,$Vehicles->id,"created");
        return $Vehicles;
    }
    public function createFromUserSeda($req){
        $Vehicles =  Vehicles::create($req);
        $this->VehiclesLogs($req["user_id"],null,$Vehicles->id,"created");
        return $Vehicles;
    }

    public function update($req){
        $Vehicles =  Vehicles::with("medias")->find($req->id);
        $Vehicles->update($req->all());
        $this->updateImages($req->images,$Vehicles);
        $this->VehiclesLogs($req->user_id,$req->admin_id,$Vehicles->id,"updated");
    }

    public function chooiesCar($id,$user_id){

        $this->removeUSerFromCurrentCar($user_id);

        $Vehicles =  Vehicles::find($id);
        $Vehicles->update([
            "user_id"=>$user_id
        ]);
        $this->VehiclesLogs($user_id,null,$Vehicles->id,"choicesCar");
    }

    public function removeUSerFromCurrentCar($id){
        $Vehicles =  Vehicles::where("user_id",$id);
        if ($Vehicles){
            $Vehicles->update([
                "user_id"=>null
            ]);
        }
    }

    public function toggelApprovel($req){
        $Vehicles =  Vehicles::find($req->id);
        $is_approved =$Vehicles->is_approve;
        $Vehicles->update([
            "is_approve"=>$is_approved?0:1
        ]);
        $this->VehiclesLogs($req->user_id,$req->admin_id,$Vehicles->id,"toggelApprovel");
    }

    public function VehiclesLogs($user_id, $admin_id = 1, $vehicle_id,$action){
        VehicleLogs::create([
            "user_id"=>$user_id,
            "vehicles_id"=>$vehicle_id,
            "admin_id"=>$admin_id,
            "day"=>now()->toDateString(),
            "action"=>$action,
        ]);
    }

    private function SaveImages($images,$model){
        foreach ($images as $key => $image){
            mediaServices::StoreMedia($image,$model->id,Vehicles::class,env(appKey())."/upload/Vehicles/",["type"=>"image","Second_type"=>$key]);
        }
    }

    private function updateImages($images,$model){
        foreach ($images as $key => $image){
            $model->medias()->where("type","driver_images")->where("Second_type",$key)->delete();
            mediaServices::StoreMedia($image,$model->id,Vehicles::class,env(appKey())."/upload/Vehicles/",["type"=>"image","Second_type"=>$key]);
        }
    }

    public function getVehiclesPerKm($id){
        $Vehicle =  Vehicles::find($id);
        return $Vehicle->perv_km;
    }
    public function updateVehiclesPerKm($id,$perKm){
        $Vehicle =  Vehicles::find($id);
        $Vehicle->update(["perv_km"=>$perKm]);
    }


}

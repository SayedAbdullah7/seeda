<?php

namespace App\services\vehicle;

use App\Models\UserVehicles;
use App\Models\VehicleLogs;

class assgineVehicleServices
{
    /*
     * id => user id
     * $data => cars Ids
     * */
    public function assgine($id,$data)
    {
        foreach ($data as $vehicle){
            $data = [
                "user_id"=>$id,
                "vehicles_id"=>$vehicle
            ];
            $this->VehiclesLogs($id,auth()->id(),$vehicle,"assignVehicles");
            UserVehicles::create($data);
        }
    }

    public function VehiclesLogs($user_id, $admin_id, $vehicle_id,$action){
        VehicleLogs::create([
            "user_id"=>$user_id,
            "vehicles_id"=>$vehicle_id,
            "admin_id"=>$admin_id,
            "day"=>now()->toDateString(),
            "action"=>$action,
        ]);
    }
}
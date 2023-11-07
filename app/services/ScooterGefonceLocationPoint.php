<?php

namespace App\services;

use App\Models\Configration;
use App\Models\Points;
use App\Models\Stations;

class ScooterGefonceLocationPoint
{

    public static function addPoint($lat , $lng){
        $stations = Stations::with("Location")->get();
        $data = Configration::where("key","point")->where("appKey",appKey())->first();

        foreach ($stations as $station){

            $dist = self::getDistanceBetweenPointsNew($station->Location->latitude,$station->Location->longitude,$lat,$lng);

            if ($dist < $data->value["radius"]){
                self::addPointToUser($data);
                return true;
            }

        }
        return false;
    }

    static function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2) {
        $theta = $longitude1 - $longitude2;
        $distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
        $distance = acos($distance);
        $distance = rad2deg($distance);
        $distance = $distance * 60 * 1.1515;

        return (round($distance* 1.609344*1000,2));
    }

    static function addPointToUser($data){
        $point = Points::where("user_id",auth()->id())->first();

        $value = $point->points + $data->value["value"];
        $point->update([
            "points"=>$value
        ]);
    }
}

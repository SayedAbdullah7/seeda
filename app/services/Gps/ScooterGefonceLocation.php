<?php

namespace App\services\Gps;

use App\Models\City;
use App\Models\Configration;
use App\Models\Points;
use App\Models\Stations;

class ScooterGefonceLocation
{

    public static function IsVhiechInZone($lat , $lng){
        $cities = city::with("Geofence")->where("appKey","527")->get();

        $check = false;
        foreach ($cities as $city){
            $checkPoint = self::pointInPolygon($city->Geofence,[$lat,$lng]);

            if ($checkPoint){
                $check = $checkPoint;
                break;
            }
        }
        return $check;
    }

    //longitude latitude
    static function pointInPolygon($polygon, $point) {
        $odd = false;
        for ($i = 0, $j = count($polygon) -1; $i < count($polygon); $i++) {
            if ((($polygon[$i]["longitude"] > $point[1]) !== ($polygon[$j]["longitude"] > $point[1]))
                && ($point[0] < (($polygon[$j]["latitude"] - $polygon[$i]["latitude"]) * ($point[1] - $polygon[$i]["longitude"]) / ($polygon[$j]["longitude"] - $polygon[$i]["longitude"]) + $polygon[$i]["latitude"]))) {
                $odd = !$odd;
            }
            $j = $i;
        }
        return $odd;
    }


}

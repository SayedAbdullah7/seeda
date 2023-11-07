<?php

namespace App\services\orderCycle;

use App\services\Map\MapService;
use Illuminate\Support\Arr;

class NearestDriverService
{
    static function getNearestDriver($drivers,$order): array
    {
        $areDis = 1000; // this will get from configuration for the project
        $lat= $order->locations->where("type","from")->first()->latitude;
        $long= $order->locations->where("type","from")->first()->longitude;

        $outDrivers = [];
        if ($drivers != null){
            foreach ($drivers as $key => $driver){
                if ($driver->Activelocations){
                    $distance = MapService::distanceBetweenTwoPoint($driver->Activelocations->latitude,$driver->Activelocations->longitude,$lat,$long);
//                    dd($distance,$areDis);
                    if ( $distance > -1 && $distance < $areDis){
                        $driver->distance = $distance;
                        $outDrivers []= $driver;
                    }
                }
            }


            if ($outDrivers){
                $outDrivers = collect($outDrivers)->sortBy('distance');
                return Arr::pluck($outDrivers, 'id');
            }
            return [];
        }

        return [];
    }

    static function getNearestDriverfromLatLong($drivers,$data)
    {
        $areDis = 600;
        $lat= $data["latitude"];
        $long= $data["longitude"];
        $outDrivers = [];
        if ($drivers != null){
            foreach ($drivers as $key => $driver){
                if ($driver->Activelocations){
                    $distance = MapService::distanceBetweenTwoPoint($driver->Activelocations->latitude,$driver->Activelocations->longitude,$lat,$long);
                    if ( $distance > -1 && $distance < $areDis){
                        $driver->distance = $distance;
                        $outDrivers []= $driver;
                    }
                }
            }

            if ($outDrivers){
                $outDrivers = collect($outDrivers)->sortBy('distance');
                return $outDrivers;
            }
            return [];
        }

        return [];
    }
}

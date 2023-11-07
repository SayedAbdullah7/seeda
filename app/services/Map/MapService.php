<?php

namespace App\services\Map;

use App\Models\Configration;
use Illuminate\Support\Facades\Http;

class MapService
{
    public static function  distanceBetweenTwoPoint($lat1, $lon1, $lat2, $lon2){
        $apiKey = Configration::where("appKey",appKey())->where("key","maps")->first();

        $mapKey ="AIzaSyAVs7MHnsNq9DhEUH5SSAdJCA4jLY6AJ34";//this key will get from database Configuration table
        $rsp = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins='.$lat1.','.$lon1.'&destinations='.$lat2.'%2C'.$lon2.'&key='.$mapKey);//$apiKey->value["api_key"]);
        $rowData = $rsp->offsetGet('rows');
        if(!isset($rowData['0']['elements']['0']['distance']['value'])){
            return -1;
        }
        if ($rowData == [])
            return 0;
        return $rowData['0']['elements']['0']['distance']['value'];
    }

    public static function  TwoPointDistanceDetails($lat1, $lon1, $lat2, $lon2){
        $apiKey = Configration::where("appKey",appKey())->where("key","maps")->first();
        $mapKey ="AIzaSyAVs7MHnsNq9DhEUH5SSAdJCA4jLY6AJ34";//this key will get from database Configuration table
        $rsp = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json?departure_time=now&units=metric&origins='.$lat1.','.$lon1.'&destinations='.$lat2.'%2C'.$lon2.'&key='.$mapKey);//$apiKey->value["api_key"]);
        $rowData = $rsp->offsetGet('rows');
        
        if ($rowData == [])
            return[
                "distance"=> 0,
                "duration"=>0
            ];
        return[
            "distance"  => $rowData['0']['elements']['0']['distance']['value'],
            "duration"  => $rowData['0']['elements']['0']['duration']['value'],
            "traffic_duration"  => $rowData['0']['elements']['0']['duration_in_traffic']['value']
        ];
    }
}

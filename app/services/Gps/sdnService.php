<?php

namespace App\services\Gps;

use App\Enums\sdnUrl;
use App\Models\Configration;
use App\services\HttpService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class sdnService
{
     static $login;
    public function __construct()
    {
        self::$login = self::login();
    }

    static function login(){
        $credtional = Configration::where("appKey",appKey())->where("key","sdn")->first();
        $data= $credtional->value;
        $data +=[
            "raw"=>true,
            "userid"=>"-200.",
            "token"=>"",
            "app_version"=>sdnUrl::apiVersion,
            "_userid"=>"na",
            "lang_code"=>"en"
        ];

        $response = HttpService::post(sdnUrl::login,["Accept"=>"application/json"],$data);
        $auth = $response->json();
        $data1 = Carbon::now();
        $data2 = Carbon::now()->addDay()->startOfDay();
        $minutes =$data2->diffInMinutes($data1);

        Cache::put(appKey().'.sdn.token', $auth["data"]["token"], $minutes);
        Cache::put(appKey().'.sdn.userid', $auth["data"]["userid"], $minutes);

        return $auth;
    }

    static function loginByAdmin(){
        $credtional = Configration::where("appKey","527")->where("key","sdn")->first();

        $data= $credtional->value;
        $data +=[
            "raw"=>true,
            "userid"=>"-200.",
            "token"=>"",
            "app_version"=>sdnUrl::apiVersion,
            "_userid"=>"na",
            "lang_code"=>"en"
        ];
        $response = HttpService::post(sdnUrl::login,["Accept"=>"application/json"],$data);

        return $response->json();
    }

    public static function AddVehicle($vehicle){
        $auth = self::login();
        $data= [
            "vehicle_name"=>"Vehicle".$vehicle->id,
            "plate_no"=>"$vehicle->imme",
            "license_start"=>"2022-08-03",
            "license_end"=>"2022-08-03",
            "current_mileage"=>0,
            "gps_unitid"=>"$vehicle->imme",
            "groupList"=>[],
            "max_speed"=>90,
            "sim_number"=>"",
            "fuel_fr"=>0,
            "density"=>0,
            "cc"=>0,
            "ve"=>0,
            "vehicle_brand"=>"",
            "vehicle_man_year"=>null,
            "vehicle_type"=>"",
            "radlz_support"=>false,
            "radlz_available"=>false,
            "radlz_mobileno"=>"",
            "userid"=>"-200",
            "token"=>$auth["data"]["token"],
            "app_version"=>sdnUrl::apiVersion,
            "_userid"=>$auth["data"]["userid"],
            "lang_code"=>"en",
            "tags"=>[
                ["key_name"=>"available","key_value"=>"true"],
                ["key_name"=>"color","key_value"=>"red"]
            ]
        ];
        $data = json_encode($data);

        $response = HttpService::post(sdnUrl::addVehicle,["Accept"=>"application/json"],["data"=>$data]);
        return $response->json()->data["token"];
    }

    public static function unlock($vehicle){
        if (Cache::has(appKey().".sdn.token")){
            $data= [
                "sensorid"=>$vehicle->imme,
                "command"=>"engine-off",
                "segid"=>"gpsdevice",
                "userid"=>"-200",
                "token"=>Cache::get(appKey().".sdn.token"),
                "app_version"=>sdnUrl::apiVersion,
                "_userid"=>Cache::get(appKey().".sdn.userid"),
                "lang_code"=>"en"
            ];
        }
        else{
            $auth = self::login();
            $data= [
                "sensorid"=>$vehicle->imme,
                "command"=>"engine-off",
                "segid"=>"gpsdevice",
                "userid"=>"-200",
                "token"=>$auth['data']['token'],
                "app_version"=>sdnUrl::apiVersion,
                "_userid"=>$auth["data"]["userid"],
                "lang_code"=>"en"
            ];
        }

        $data = json_encode($data);
        $response = HttpService::post(sdnUrl::cutOffOnVehicle,["Accept"=>"application/json"],["data"=>$data]);

        $code = $response->json();
        if ($code["type"] == "ok"){
            return ["status"=>true,"command"=>$code["value"]];
        }

        if ($code["type"]=="error" && $code["value"]==2000){
            Cache::delete(appKey().".sdn.token");
            Cache::delete(appKey().".sdn.userid");
            self::unlock($vehicle);
        }
        return ["status"=>false,"command"=>$code["value"]];
    }

    public static function lock($vehicle){
        if (Cache::has(appKey().".sdn.token")){
            $data= [
                "sensorid"=>$vehicle->imme,
                "command"=>"engine-on",
                "segid"=>"gpsdevice",
                "userid"=>"-200",
                "token"=>Cache::get(appKey().".sdn.token"),
                "app_version"=>sdnUrl::apiVersion,
                "_userid"=>Cache::get(appKey().".sdn.userid"),
                "lang_code"=>"en"
            ];
        }
        else{
            $auth = self::login();
            $data= [
                "sensorid"=>$vehicle->imme,
                "command"=>"engine-on",
                "segid"=>"gpsdevice",
                "userid"=>"-200",
                "token"=>$auth['data']['token'],
                "app_version"=>sdnUrl::apiVersion,
                "_userid"=>$auth["data"]["userid"],
                "lang_code"=>"en"
            ];
        }

        $data = json_encode($data);
        $response = HttpService::post(sdnUrl::cutOffOnVehicle,["Accept"=>"application/json"],["data"=>$data]);
        $code = $response->json();

        if ($code["type"] == "ok"){
            return ["status"=>true,"command"=>$code["value"]];
        }

        if ($code["type"]=="error" && $code["value"]==2000){
            Cache::delete(appKey().".sdn.token");
            Cache::delete(appKey().".sdn.userid");
            self::unlock($vehicle);
        }
        return ["status"=>false,"command"=>null];

    }

    public static function lockByAdmin($vehicle){
        Log::error("lockByAdmin");
        $auth = self::loginByAdmin();
        $data= [
            "sensorid"=>"$vehicle->imme",
            "command"=>"engine-on",
            "segid"=>"gpsdevice",
            "userid"=>"-200",
            "token"=>$auth["data"]["token"],
            "app_version"=>sdnUrl::apiVersion,
            "_userid"=>$auth["data"]["userid"],
            "lang_code"=>"en"
        ];

        $data = json_encode($data);
        $response = HttpService::post(sdnUrl::cutOffOnVehicle,["Accept"=>"application/json"],["data"=>$data]);
        $code = $response->json();

        if ($code["type"] == "ok"){
            return ["status"=>true,"command"=>$code["value"]];
        }

        if ($code["type"]=="error" && $code["value"]==2000){
            Cache::delete("527.sdn.token");
            Cache::delete("527.sdn.userid");
            self::lockByAdmin($vehicle);
        }
        return ["status"=>false,"command"=>null];
    }

    public static function getVehicleById($vehicle){
        if (Cache::has(appKey().".sdn.token")){
            $data= [
                "userid"=>"-200",
                "token"=>Cache::get(appKey().".sdn.token"),
                "app_version"=>sdnUrl::apiVersion,
                "_userid"=>Cache::get(appKey().".sdn.userid"),
                "lang_code"=>"en",
                "vehicleid"=>$vehicle->gps_id
            ];
        }
        else{

            $auth = self::login();
            $data= [
                "userid"=>"-200",
                "token"=>$auth["data"]["token"],
                "app_version"=>sdnUrl::apiVersion,
                "_userid"=>$auth["data"]["userid"],
                "lang_code"=>"en",
                "vehicleid"=>$vehicle->gps_id
            ];
        }

        $data = json_encode($data);

        $response = HttpService::post(sdnUrl::getVehicleById,["Accept"=>"application/json"],["data"=>$data]);

        $resp = $response->json();
        if ($resp["type"]=="error" && $resp["value"]==2000){
            Cache::delete(appKey().".sdn.token");
            Cache::delete(appKey().".sdn.userid");
            self::getVehicleById($vehicle);
        }
        return $response->json();
    }

    public static function getVehicleByIdInAdmin($vehicle){
        $auth = self::loginByAdmin();
        $data= [
            "userid"=>"-200",
            "token"=>$auth["data"]["token"],
            "app_version"=>sdnUrl::apiVersion,
            "_userid"=>$auth["data"]["userid"],
            "lang_code"=>"en",
            "vehicleid"=>$vehicle->gps_id
        ];

        $data = json_encode($data);

        $response = HttpService::post(sdnUrl::getVehicleById,["Accept"=>"application/json"],["data"=>$data]);
        return $response->json();
    }

    public static function getVehicleInAdmin(){
        $auth = self::loginByAdmin();
        $data= [
            "userid"=>"-200",
            "token"=>$auth["data"]["token"],
            "app_version"=>sdnUrl::apiVersion,
            "_userid"=>$auth["data"]["userid"],
            "lang_code"=>"en",
        ];

        $data = json_encode($data);

        $response = HttpService::post(sdnUrl::getVehicle,["Accept"=>"application/json"],["data"=>$data]);
        return $response->json();
    }

    public static function getLocation($startTime,$endTime,$imme)
    {
        $auth = self::login();
        $data = [
            "start_time" => $startTime,//"2023-04-05T06:35:00.000Z",//
            "end_time" =>$endTime,//"2023-04-10T07:34:44.000Z", //
            "min_speed" => 0,
            "start" => 1000,
            "limit" => 500,
            "objectids" =>[$imme],//["350424065342139"],//
            "playmode" => false,
            "userid" => "-200.",
            "token" => $auth["data"]["token"],
            "app_version" => 49,
            "_userid" => $auth["data"]["userid"],
            "lang_code" => "en"
        ];
        $data = json_encode($data);

        $response = HttpService::post(sdnUrl::getLocation, ["Accept" => "application/json"], ["data" => $data]);
        return $response->json();
    }

    static function loginCallback($appKey){
        $credtional = Configration::where("appKey",$appKey)->where("key","sdn")->first();
        $data= $credtional->value;
        $data +=[
            "raw"=>true,
            "userid"=>"-200.",
            "token"=>"",
            "app_version"=>sdnUrl::apiVersion,
            "_userid"=>"na",
            "lang_code"=>"en"
        ];

        $response = HttpService::post(sdnUrl::login,["Accept"=>"application/json"],$data);
        $auth = $response->json();
        $data1 = Carbon::now();
        $data2 = Carbon::now()->addDay()->startOfDay();
        $minutes =$data2->diffInMinutes($data1);

        Cache::put(appKey().'.sdn.token', $auth["data"]["token"], $minutes);
        Cache::put(appKey().'.sdn.user_id', $auth["data"]["userid"], $minutes);

        return $auth;
    }

    public static function unlockCallback($vehicle,$appKey){
        if (Cache::has($appKey.".sdn.token")){
            $data= [
                "sensorid"=>$vehicle->imme,
                "command"=>"engine-off",
                "segid"=>"gpsdevice",
                "userid"=>"-200",
                "token"=>Cache::get($appKey.".sdn.token"),
                "app_version"=>sdnUrl::apiVersion,
                "_userid"=>Cache::get($appKey.".sdn.userid"),
                "lang_code"=>"en"
            ];
        }
        else{
            $auth = self::loginCallback($appKey);
            $data= [
                "sensorid"=>$vehicle->imme,
                "command"=>"engine-off",
                "segid"=>"gpsdevice",
                "userid"=>"-200",
                "token"=>$auth['data']['token'],
                "app_version"=>sdnUrl::apiVersion,
                "_userid"=>$auth["data"]["userid"],
                "lang_code"=>"en"
            ];
        }
        $data = json_encode($data);

        $response = HttpService::post(sdnUrl::cutOffOnVehicle,["Accept"=>"application/json"],["data"=>$data]);

        $code = $response->json();
        if ($code["type"] == "ok"){
            return ["status"=>true,"command"=>$code["value"]];
        }

        if ($code["type"]=="error" && $code["value"]==2000){
            Cache::delete(appKey().".sdn.token");
            Cache::delete(appKey().".sdn.userid");
            self::unlock($vehicle);
        }
        return ["status"=>false,"command"=>$code["value"]];
//        if ($code["type"] == "ok")
//            return true;
//
//        if ($code["type"]=="error" && $code["value"]==2000){
//            Cache::delete(appKey().".sdn.token");
//            Cache::delete(appKey().".sdn.userid");
//            self::unlockCallback($vehicle,$appKey);
//        }
//        return false;
    }

    static function checkCommend($vehicle,$command){
        $auth = self::login();

        $data= [
            "registerid"=>"$vehicle->imme",
            "segid"=>"gpsdevice",
            "command_id"=>$command,
            "userid"=>"-200",
            "token"=>$auth["data"]["token"],
            "app_version"=>sdnUrl::apiVersion,
            "_userid"=>$auth["data"]["userid"],
            "lang_code"=>"en"
        ];

        $data = json_encode($data);

        $response = HttpService::post(sdnUrl::getVCommand,["Accept"=>"application/json"],["data"=>$data]);

        return $response->json();
    }

    static function checkCommendByAdmin($vehicle,$command){
        $auth = self::loginByAdmin();

        $data= [
            "registerid"=>"$vehicle->imme",
            "segid"=>"gpsdevice",
            "command_id"=>$command,
            "userid"=>"-200",
            "token"=>$auth["data"]["token"],
            "app_version"=>sdnUrl::apiVersion,
            "_userid"=>$auth["data"]["userid"],
            "lang_code"=>"en"
        ];

        $data = json_encode($data);

        $response = HttpService::post(sdnUrl::getVCommand,["Accept"=>"application/json"],["data"=>$data]);

        return $response->json();
    }
    public static function updateCommand($vehicle,$command){
        if (Cache::has(appKey().".sdn.token")){
            $data =  [
                "sensorid"=>$vehicle->imme,
                "segid"=>"gpsdevice",
                "status"=>"cancelled",
                "command_id"=>$command,
                "userid"=>"-200",
                "token"=>Cache::get(appKey().".sdn.token"),
                "accountid"=>"153c1b30-a6dc-11ed-ab58-9795c4c97dc0",
                "app_version"=>49,
                "_userid"=>Cache::get(appKey().".sdn.userid"),
                "lang_code"=>"en"
            ];
        }
        else{
            $auth = self::login();
            $data =  [
                "sensorid"=>$vehicle->imme,
                "segid"=>"gpsdevice",
                "status"=>"cancelled",
                "command_id"=>$command,
                "userid"=>"-200",
                "token"=>$auth['data']['token'],
                "accountid"=>"153c1b30-a6dc-11ed-ab58-9795c4c97dc0",
                "app_version"=>49,
                "_userid"=>$auth["data"]["userid"],
                "lang_code"=>"en"
            ];
        }

        $data = json_encode($data);
        $response = HttpService::post(sdnUrl::updateCommand,["Accept"=>"application/json"],["data"=>$data]);
        $code = $response->json();

        if ($code["type"] == "ok"){
            return ["status"=>true,"command"=>$code["value"]];
        }

        if ($code["type"]=="error" && $code["value"]==2000){
            Cache::delete(appKey().".sdn.token");
            Cache::delete(appKey().".sdn.userid");
            self::updateCommand($vehicle,$command);
        }
        return ["status"=>false,"command"=>null];

    }

}

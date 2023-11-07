<?php

namespace App\Jobs;

use App\Models\ActiveLocation;
use App\Models\Location;
use App\Models\Vehicles;
use App\services\Gps\ScooterGefonceLocation;
use App\services\Gps\sdnService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Modules\Sonic\Http\Controllers\Api\getScooterLiveTrackController;

class getScooterLocations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = sdnService::getVehicleInAdmin();
        $vehicles = Vehicles::where("appKey",'527')->get();
        Log::error("ss",$data);
        foreach ($data["data"] as $location){
            $vehicle = $vehicles->where("gps_id",$location["vehicleid"])->first();
            if ($vehicle){
                $lang = $location["last_location"]["lng"];
                $lat = $location["last_location"]["lat"];
                $battery = ($location["last_location"]["fuel"]/100)*50;

                Location::where('locationable_type',"like","%Vehicles%")->where("locationable_id",$vehicle->id)->update([
                    'latitude'=>$lat,
                    'longitude'=>$lang,
                ]);

                $in_Zone = ScooterGefonceLocation::IsVhiechInZone($lat,$lang);

                if ($in_Zone){
                    $vehicle->update(['battary'=>$battery]);
                }else{
                    $vehicle->update(['battary'=>$battery,'in_zone'=>false]);
                }
            }
        }
    }
}

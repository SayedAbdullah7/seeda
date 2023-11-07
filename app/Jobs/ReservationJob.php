<?php

namespace App\Jobs;

use App\Models\VehicleReservation;
use App\Models\Vehicles;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReservationJob implements ShouldQueue
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
        $vehicles_ids = VehicleReservation::where("active",1)->where("appKey",appKey())->where("to","<",now())->pluck("vehicles_id");
        VehicleReservation::where("active",1)->where("appKey",appKey())->where("to","<",now())->update(["active"=>0]);

        Vehicles::whereIn("id",$vehicles_ids->toArray())->update(["is_open"=>0]);
    }
}

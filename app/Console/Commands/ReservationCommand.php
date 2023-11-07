<?php

namespace App\Console\Commands;

use App\Models\VehicleReservation;
use App\Models\Vehicles;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ReservationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservation:reservation_end';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reservation';

    /**
     * Execute the console command.
     *
     *
     */
    public function handle()
    {
        $vehicles_ids = VehicleReservation::where("active","=",1)->where("to","<=",now()->toDateTimeString())->pluck("vehicles_id");
        VehicleReservation::where("active","=",1)->where("to","<=",now()->toDateTimeString())->update(["active"=>0]);

        Vehicles::whereIn("id",$vehicles_ids->toArray())->update(["is_open"=>0]);
    }
}

<?php

namespace App\Jobs;

use App\Enums\orderStatus;
use App\Models\Vehicles;
use App\Response\ApiResponse;
use App\services\Gps\sdnService;
use App\services\order\OrderDetailsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckScooterWorkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public $order,public string $queueName)
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
        $order = $this->order;
        if ($order->status == orderStatus::waiting){
            $vehicle = Vehicles::find($order->scooter_id);
            $response = sdnService::checkCommendByAdmin($vehicle,$order->on_command);
            if ($response["data"][0]["status"] == "done"){
                $order->update(["status"=>orderStatus::start]);
                OrderDetailsService::startTime($order->id);
            }else{
                sdnService::lockByAdmin($vehicle);
                $order->delete();
            }
        }
    }
}

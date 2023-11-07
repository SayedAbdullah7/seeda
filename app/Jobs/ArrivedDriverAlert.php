<?php

namespace App\Jobs;

use App\Enums\orderStatus;
use App\Events\generalEventWithAppKey;
use App\Models\OrderSentToDriver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ArrivedDriverAlert implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private $order,public string $queueName)
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
        $driver = OrderSentToDriver::where(["order_id"=>$this->order->id,"status"=>orderStatus::arrived])->first();

        new generalEventWithAppKey($this->order,$this->order->appKey,[$driver->user_id],'ArrivedDriverAlert',__("api.ArrivedDriverAlert"));
    }
}

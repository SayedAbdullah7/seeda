<?php

namespace App\Jobs;

use App\Enums\orderStatus;
use App\Events\generalEvent;
use App\Events\generalEventWithAppKey;
use App\Models\Order;
use App\Models\OrderSentToDriver;
use App\services\orderCycle\sendSerialCycle;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AutoNextDriverJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private $order,public string $queueName)
    {
        Log::error("cons");
    }
//        DB::table('jobs')->where("payload", "like", "%AutoNextDriverJob-{$room->id}-order_id%")->delete();

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::error("handel");
//        Log::info('AutoNextDriverJob function');
        \Illuminate\Support\Facades\Storage::put('AutoNextDriverJob.json', json_encode([''=>'AutoNextDriverJob']));

        $drivers = OrderSentToDriver::where(["order_id"=>$this->order->id,"status"=>orderStatus::waiting])->get();
        if ($drivers){
            foreach ($drivers as $driver){
                new generalEventWithAppKey($this->order,$this->order->appKey,[$driver->user_id],'AutoCancelDriver',__("api.order has been cancelled automatic "));
            }
            $drivers->update(["status"=>orderStatus::cancel]);
        }

        $WattingDriverList =OrderSentToDriver::where('order_id',$this->order->id)
            ->where('status',orderStatus::waiting)->pluck('user_id');
        if (count($WattingDriverList) == 0){
            new generalEventWithAppKey($this->order,$this->order->appKey,[$this->order->user_id],'NoDriverAcceptedOrFounded',__("api.order has been cancelled automatic "));
            Order::where("id",$this->order->id)->update([
                'status'=>orderStatus::cancel,
                'cancel_reason'=>"no Driver Accept"
            ]);
        }else{
            $count = count($WattingDriverList);
            $max = min($count, 3);
            for ($x = 0; $x <= $max; $x++) {
                \Illuminate\Support\Facades\Storage::put('AutoNextDriverJob_generalEventWithAppKey.json', json_encode([''=>'generalEventWithAppKey']));
//                Log::info('AutoNextDriverJob new generalEventWithAppKey '.$x);
                new generalEventWithAppKey($this->order,$this->order->appKey,[$WattingDriverList[$x]],'newOrder',__("api.you have new order"));
            }
        }

        sendSerialCycle::sendNext($this->order);
    }
}

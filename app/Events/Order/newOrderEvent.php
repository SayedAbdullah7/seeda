<?php

namespace App\Events\Order;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\User;

class newOrderEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $room= "seda.users.";
    private $order;
    private $msg;

    public function __construct( $order,array $users,$msg=null)
    {
        $this->room= env(request()->header('appKey')).".users.";
        $this->order= $order;
        $this->msg= $msg;
        $link = env("HTTP_SOCKET");

        $data= [
            "room"=>$this->room,
            "to"=>implode(',',$users),
            "data"=>json_encode($this->data()),
        ];
        try{
            $response = Http::acceptJson()
            ->timeout(5)
            ->post($link, $data);
        }catch(\Throwable $error){
            \Log::info('socket not work');
        }
    }

 
    public function data()
    {
        return 
        [
            'event'=>'newOrder',
            'message'=>$this->msg,
            'order'=>$this->order
        ];
    }

    
}

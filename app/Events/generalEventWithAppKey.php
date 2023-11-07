<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class generalEventWithAppKey
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( private $data,private $appKey,private array $users,private $event,private $msg=null)
    {
        $this->room= $this->appKey.".users.";
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
            Log::info('socket not work');
        }
    }


    public function data()
    {
        return
            [
                'event'=>$this->event,
                'message'=>$this->msg,
                'data'=>$this->data
            ];
    }
}

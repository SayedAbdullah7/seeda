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
use App\User;

class generalEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $room;
    private $data;
    private $msg;
    private $event;

    public function __construct( $data,array $users,$event,$msg=null)
    {
        $this->room= appKey().".users.";
        $this->data= $data;
        $this->event= $event;
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
        \Illuminate\Support\Facades\Storage::put('generalEvent.json', json_encode([''=>'']));
        return
        [
            'event'=>$this->event,
            'message'=>$this->msg,
            'data'=>$this->data
        ];
    }


}

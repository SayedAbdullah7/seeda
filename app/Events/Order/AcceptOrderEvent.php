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
use App\Models\User;

class AcceptOrderEvent
{
    private string $room;
    private array  $users;
    private $user;
    private string $string;
    
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public function __construct(User $user,$users)
    {
        $this->room= env(request()->header('appKey')).".users.";

        $this->user= $user;
        $this->users= $users;
        $link = env("HTTP_SOCKET");

        $data= [
            "room"=>$this->room,
            "to"=>implode(',',$this->users),
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
            'event'=>'acceptOrder',
            'message'=>__("api.your order has been accepted"),
            'data'=>$this->user,
        ];
    }

    
}

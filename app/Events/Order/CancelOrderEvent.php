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
use App\Models\Order;
use App\Models\User;

class CancelOrderEvent
{
    private $name= "seda.users.";
    
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    private $order;

    public function __construct(Order $order)
    {
        $this->order= $order;
        $link = env("HTTP_SOCKET")."/emit";

        $data= [
            "room"=>$this->name,
            "to"=>$this->to(),
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


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function to()
    {
        $ids= User::activeAdmin()->typeAdmin()->admin()->pluck('id');
        $ids->push($this->order->user_id_shop);
        return implode(',',$ids->toArray());
    }

    public function data()
    {
        $user_accept='';
        if($this->order->user_id_accept){
            $user= User::find($this->order->user_id_accept);
            $user_accept= ' من قبل '.$user->name;
        }
        return 
        [
            'message'=>"  تم إلغاء الطلب"." #".$this->order->order_type->title . $user_accept,
            'url'=>'/admin/ar/orders/'.$this->order->id
        ];
    }

    
}

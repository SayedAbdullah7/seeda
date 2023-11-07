<?php

namespace App\services;

use App\Models\Rooms;
use App\Models\UserRooms;

class roomService
{
    public static function startRoom($type,$users,$order_id=0){
        $data = ["type"=>$type,"appKey"=>appKey()];
        if ($order_id >0)
            $data["order_id"]=$order_id;

        $room = Rooms::create($data);

        foreach ($users as $user){
            UserRooms::create([
                "user_id"=>$user,
                "room_id"=>$room->id
            ]);
        }
    }

    public static function startTaskRoom($type,$users,$task_id=0){
        $data = ["type"=>$type,"appKey"=>appKey()];
        if ($task_id >0)
            $data["task_id"]=$task_id;

        $room = Rooms::create($data);

        foreach ($users as $user){
            UserRooms::create([
                "user_id"=>$user,
                "room_id"=>$room->id
            ]);
        }
    }
}
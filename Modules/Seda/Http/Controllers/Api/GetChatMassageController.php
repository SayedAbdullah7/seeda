<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Http\Resources\userResource;
use App\Models\Massages;
use App\Models\Rooms;
use App\Response\ApiResponse;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Seda\Transformers\GetChatMassageResource;

class GetChatMassageController extends Controller
{
    public function index(Request $request){
        $room = $this->getRoomData($request->order_id);

        //        $massages = Massages::with(["User","Medias"])->where("room_id",$room->id)->orderBy("id","DESC")->paginate(10);
        $massages = Massages::with(["User","Medias"])->where("room_id",$room->id)->get();
        $data= GetChatMassageResource::collection($massages);

        $user_from  = (auth()->id() == $room->UserRooms[0]->user_id)?$room->UserRooms[0]->User:$room->UserRooms[1]->User;
        $user_to  = (auth()->id() == $room->UserRooms[0]->user_id)?$room->UserRooms[1]->User:$room->UserRooms[0]->User;

        $response = new ApiResponse(200,$massages->count()?__('api.chat massage retrieved successfully'):__('api.not massage retrieved'),[
            'massages'=>$data,
            'from_user'=>new userResource($user_from),
            'to_user'=>new userResource($user_to),
            'room_id'=>$room->id
        ]);
        return $response->send();
    }

    public function getRoomData($id){
        $query = Rooms::query();

        $query = $query->with('UserRooms.User')->where("order_id",$id);

        return $query->first();
    }
}

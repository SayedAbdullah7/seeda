<?php

namespace App\Http\Controllers\Api\Chat;

use App\Events\generalEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Chat\ChatMassageRequest;
use App\Http\Resources\Chat\MassagesResource;
use App\Http\Resources\Chat\RoomsResource;
use App\Models\Massages;
use App\Models\Rooms;
use App\Models\UserRooms;
use App\services\mediaServices;
use App\Response\ApiResponse;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ChatController extends Controller
{
    public function sendMassage(ChatMassageRequest $request){

        $data =$request->validated();
        $data["to_user_id"]=$data["user_id"];
        $data["user_id"]=auth()->id();

        if ( $data["to_user_id"]== auth()->id())
            return (new ApiResponse (406,__("api.you can't send massage to yourself"),[]))->send();
        $massage = Massages::create($data);

        if ($request->hasFile("media.filename"))
            mediaServices::StoreMedia($request->file("media.filename"),$massage->id,Massages::class,env(appKey())."/upload/Chat/",$data["media"]);


        $roomUser= UserRooms::where("room_id",$request->room_id)->where("user_id","<>",auth()->id())->select("user_id")->first();
        $response = new ApiResponse(200,__('api.successfully'),[
            "data"=>new MassagesResource($massage)
        ]);

        new generalEvent(new MassagesResource($massage),[$roomUser->user_id],'you have new message',__("api.you have new massage"));

        return $response->send();
    }

    public function getRooms(Request $request){

        $query = Rooms::query();
        $query = $query->with('UserRooms.User')->whereHas('UserRooms', function (Builder $query) {
                $query->where('user_id', auth()->id());
            });
        if ($request->has("order_id")){
            $query = $query->where("order_id",$request->order_id);
        }
        $rooms = $query->get();

        $data= RoomsResource::collection($rooms);

        $response = new ApiResponse($rooms->count()?200:204,__('api.your massages'),[
            'Rooms'=>$data
        ]);
        return $response->send();
    }

    public function getMassage($id){
        $massages = Massages::with(["User","Medias"])->where("room_id",$id)->get();

        $data= MassagesResource::collection($massages);

        $response = new ApiResponse($massages->count()?200:204,__('api.your massages'),[
            'massages'=>$data
        ]);
        return $response->send();
    }

}

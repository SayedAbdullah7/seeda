<?php

namespace App\Http\Controllers\Api;

use App\Events\generalEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\Chat\MassagesResource;
use App\Http\Resources\NotifactionResource;
use App\Models\Notify;
use App\Models\User;
use App\Response\ApiResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(){
        $notify = Notify::where("user_id",auth()->id())->orderBy('created_at', 'DESC')->get();

        $data= NotifactionResource::collection($notify) ;

        $response = new ApiResponse(200,__('api.Notification retrieved successfully'),[
            'massages'=>$data
        ]);
        return $response->send();
    }

    public function show($id){
        $notify = Notify::with("notification")->where("id",$id)->where("user_id",auth()->id())->first();
        $data =  new NotifactionResource($notify);
        $response = new ApiResponse($notify->count()?200:204,__('api.Notification retrieved successfully'),[
            'massages'=>$data
        ]);
        return $response->send();
    }

    public function markAllAsRead(Request $request){

        Notify::where("user_id",auth()->id())->update(["is_seen"=>1]);

        $response = new ApiResponse(200,__('api.successfully'),[]);

        return $response->send();
    }

    public function markAsRead($id){

        $check = Notify::with("notification")->where("id",$id)->where("user_id",auth()->id())->first();

        if ($check){

            if ($check->notification->appKey == appKey()){
                Notify::where("id",$id)->update(["is_seen"=>1]);
                $response = new ApiResponse(200,__('api.successfully'),[]);
            }else{
                $response = new ApiResponse(406,__('api.NotHaveAnyAccessToThisId'),[]);
            }
        }else{
            $response = new ApiResponse(406,__('api.NotHaveAnyAccessToThisId'),[]);
        }
        return $response->send();
    }
}

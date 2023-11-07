<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Response\ApiResponse;
use Illuminate\Http\Request;

class OnlineController extends Controller
{
    public function onlineUserToggel(Request $request){
        if ($request->is_online == null){
            $user = auth()->user();
            $is_online = $user->is_online;
            if ($is_online){
                $user->update(["is_online"=>0]);
                return (new ApiResponse(200,"api.you toggle online successfully",["is_online"=>false]))->send();
            }else{
                $user->update(["is_online"=>1]);
                return (new ApiResponse(200,"api.you toggle online successfully",["is_online"=>true]))->send();
            }
        }else{
            $user = auth()->user();
            $user->update(["is_online"=>$request->is_online]);
            return (new ApiResponse(200,"api.you toggle online successfully",["is_online"=>false]))->send();
        }
    }
}

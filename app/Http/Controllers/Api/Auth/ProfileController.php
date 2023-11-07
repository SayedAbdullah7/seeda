<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetProfileRequest;
use App\Http\Resources\userResource;
use App\Models\User;
use App\Response\ApiResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    public function getProfile(Request $request)
    {
        return GeneralApiFactory('GetProfileController',Request::class);
    }
    public function getUserById(Request $request)
    {
        $request->validate([
            "id"=>"required"
        ]);

        $user = User::where('id',$request->id)->where("appKey",appKey())->first();
        if ($user){
            $userResource= new userResource($user);

            return ( new ApiResponse(200,__('api.retrieved successfully'),['user'=>$userResource]))->send();
        }

        return ( new ApiResponse(406,__('api.you do not have the right  to get this data'),[]))->send();
    }
}

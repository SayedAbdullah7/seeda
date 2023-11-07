<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginBySocialRequest;
use App\Http\Resources\userResource;
use App\Integrations\socialLogin\socialLoginFactory;
use App\Models\User;
use App\Response\ApiResponse;
use Illuminate\Http\Request;

class LoginBySocialController extends Controller
{
//    public function index(){
//        return GeneralApiFactory('LoginBySocialController',LoginBySocialRequest::class);
//    }
    public function index(LoginBySocialRequest $request)
    {
        $data= socialLoginFactory::Login($request->token,$request->provider);

        $userData = ['name' => $data->name];
        if ($data->email != null)
            $userData += ["email" => $data->email];

        if ($data->phone != null)
            $userData += ["phone" => $data->phone];

//        $userData += ["providerId" => $data->id];
        $userData += ['appKey'=>request()->header('appKey')];

        if ($request->type != null)
            $userData += ["type" => $request->type];

        $user  =user::CreateOrUpdate($userData);

        if ($user->phone){
            $this->sendSms($request->phone);
            $user= new userResource($user);
            $user->token=   $user->createToken('verify_otp', ['verify_otp']);
            return
                ( new ApiResponse(200,__('api.opt validation successfully'),['user'=>$user]))
                    ->send();
        }else{
            $user= new userResource($user);
            $user->token=   $user->createToken('send_otp', ['send_otp']);
            return
                ( new ApiResponse(300,__('api.enter Phone Two Validate'),['user'=>$user]))
                    ->send();
        }
    }
}

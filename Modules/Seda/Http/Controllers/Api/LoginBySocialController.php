<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Http\Resources\userResource;
use App\Integrations\socialLogin\socialLoginFactory;
use App\Models\User;
use App\Response\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LoginBySocialController extends Controller
{


    public function index(Request $request)
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

<?php

namespace App\services;

use App\Http\Resources\userResource;
use App\Integrations\sms\facrotyMethodSms;
use App\Models\Countries;
use App\Models\User;
use App\Response\ApiResponse;
use Illuminate\Support\Facades\Auth;

class otpService
{
    public static function sendLoginOtp($request,$otp,$user){

        $country = Countries::where("appKey",appKey())->where("dial_code",$request->dial_code)->first();
        if (!$country){
            return
                ( new ApiResponse(406,__("api.this application not valid in your country"),[]))->send();
        }
        if ($user->currentAccessToken())
            $user->currentAccessToken()->delete();
        if ($user->phone == null){
			if ($request->type == "employee")
                return (new ApiResponse(406,__("api.ConnnectYourCompanyOwnerToAddYouAsStaff"),[]))->send();
            $user = User::create([
                'phone'=>$request->phone,
                'country_id'=>$country->id,
                'otp'=>$otp,
                'deleted_at'=>null,
                'last_otp_sent'=>date('Y-m-d H:i:s'),
                'appKey'=>request()->header('appKey'),
                'type'=>$request->type
            ]);
            facrotyMethodSms::sms($request->phone,$otp);
            $user->token=   $user->createToken('verify_otp', ['verify_otp']);
            return
                ( new ApiResponse(200,__('api.you create your account use opt validation to validated your account')
                    ,['token'=>$user->token,'otp'=>$otp]))->send();
        }else{
            if ($user->type == null ||strtoupper($user->type) == strtoupper($request->type)){
                //check if user exists but deleted .
                if($user->deleted_at){
                    $user->update([
                        'otp'=>$otp,
                        'deleted_at'=>null,
                        'last_otp_sent'=>date('Y-m-d H:i:s'),
                        'appKey'=>request()->header('appKey')
                    ]);
                    facrotyMethodSms::sms($request->phone,$otp);

                    $user= new userResource($user);
                    $user->token=   $user->createToken('verify_otp', ['verify_otp']);
                    return
                        ( new ApiResponse(200,__('api.you deleted your account use opt validation to retrieve your account')
                            ,['token'=>$user->token,'otp'=>$otp]))->send();
                }
                //check if user exists before.
                if($user->id && self::checkResenTwoMinutes($user))
                    return
                        ( new ApiResponse(412,__('api.checkSentTwoMinutes'),[]))
                            ->send();

                $user->update([
                    'phone'=>$request->phone,
                    'otp'=>$otp,
                    'last_otp_sent'=>date('Y-m-d H:i:s'),
                    'appKey'=>request()->header('appKey')
                ]);
                facrotyMethodSms::sms($request->phone,$otp);

                $token=   $user->createToken('verify_otp', ['verify_otp']);
                return
                    ( new ApiResponse(200,__('api.opt validation successfully'),['token'=>$token,'otp'=>$otp]))
                        ->send();
            }else{
                return
                    ( new ApiResponse(406,__("api.youCan'tLoginWithThisNumber"),[]))->send();
            }
        }
    }

    public static function LoginOtpVerify($request){
        if(Auth::user()->otp != $request->otp)
            return
                ( new ApiResponse(412,__('api.invalidOTP'),[]))
                    ->send();

        Auth::user()->currentAccessToken()->delete();
        Auth::user()->update([
            'is_verified'=>1,
        ]);
        if(Auth::user()->name){
            $token=   Auth::user()->createToken('users', ['users']);
            $user_id = Auth::user()->medias->where("type","userId")->count() > 1;
            return
                ( new ApiResponse(200,__('api.otpValidationSuccessfully'),['token'=>$token,"social"=>false,'user'=>true,'user_id'=>$user_id]))
                    ->send();
        }else{
            $token=   Auth::user()->createToken('register',['register']);
            return
                ( new ApiResponse(200,__('api.otpValidationSuccessfully'),['token'=>$token,"social"=>false,'user'=>false]))
                    ->send();
        }
    }

    static function checkResenTwoMinutes($user) :bool
    {
        // get datetime from 2 minute ;
        $datetime= date('Y-m-d H:i:s', strtotime('-119 seconds'));
        $check=$user->last_otp_sent >= $datetime;
        return $check;
    }
}

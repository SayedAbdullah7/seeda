<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginOtpVerifyRequest;
use App\Http\Requests\VerifyPhoneRequest;
use App\Integrations\sms\facrotyMethodSms;
use App\Models\User;
use App\services\otpService;

class LoginController extends Controller
{
    public function sendOtp(VerifyPhoneRequest $request){
        $phone = $request->phone;
        $this->otp = rand(1000,9999);
        facrotyMethodSms::sms($phone,$this->otp);
    }
    public function otpValidation(){

    }

    public function sendLoginOtp(VerifyPhoneRequest $request){
        $otp = rand(1000,9999);
        $user= User::getByPhone()->first()??new USer();
        return otpService::sendLoginOtp($request,$otp,$user);
    }

    public function loginOtpValidation(LoginOtpVerifyRequest $request){
        return otpService::LoginOtpVerify($request);
    }

    public function loginWithEmail(){

    }

    public function  loginWithEmailAndPass(){

    }
    public function  loginWithPhoneAndPass(){

    }
    public function  loginWithUserNameAndPass(){

    }

}

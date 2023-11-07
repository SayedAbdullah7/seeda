<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Response\ApiResponse;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout(){
        Auth::user()->currentAccessToken()->update([
            "expires_at"=>now()
        ]);
        return
            ( new ApiResponse(200,__('api.you logout successfully!'),[]))->send();
    }
}

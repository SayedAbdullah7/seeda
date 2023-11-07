<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Response\ApiResponse;
use Illuminate\Http\Request;
class UpdateFcmController extends Controller
{
    public function updateFcm(Request $request){

        $request->validate([
            "fcm"=>"required"
        ]);
        $user = auth()->user();

        $user->update(["fcm"=> $request->fcm]);

        return (new ApiResponse(200,__("api.fcm Updated Successfully"),[]))->send();
    }
}

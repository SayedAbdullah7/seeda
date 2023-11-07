<?php

namespace App\Http\Controllers\Api\Rate;

use App\Http\Controllers\Controller;
use App\Http\Resources\Rate\GetMyRateResource;
use App\Models\Rate;
use App\Response\ApiResponse;

class GetMyRateController extends Controller
{
    public function index(){
        $rates =  Rate::where("user_id",auth()->id())->get();
        $resposne = new ApiResponse(200,__('api.successfully',),[
            'rates'=>GetMyRateResource::collection($rates),
        ]);
        return $resposne->send();
    }

}

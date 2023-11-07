<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RateResource;
use App\Models\OrderSentToDriver;
use App\Models\User;
use App\Response\ApiResponse;
use App\Models\Rate;
use Illuminate\Http\Request;

class GetRateController extends Controller
{
    public $request;
    public function index( Request $request)
    {
        $user =  User::where("id",auth()->id())->with(["rates.User","Order"])->first();
        $resposne = new ApiResponse(200,__('api.successfully',),[
            'avg'=>($user->rates->sum('rate')/($user->rates->count() == 0)?1:$user->rates->count()),
            'num_of_user'=>$user->rates->count(),
            'num_of_trips'=>$user->Order->count(),
            'rates'=>RateResource::collection($user->rates),
        ]);
        return $resposne->send();
    }

}

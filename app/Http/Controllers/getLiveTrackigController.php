<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class getLiveTrackigController extends Controller
{
    public function index(Request $request){
        $appKey = $request->appKey;
       return GeneralApiFactoryWithApp($appKey,"getLiveTrackigController",Request::class);
    }
}

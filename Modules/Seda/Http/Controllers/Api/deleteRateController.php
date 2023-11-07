<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Models\Rate;
use App\Response\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class deleteRateController extends Controller
{
    public function index(Request $request){

        $rate = Rate::find($request->id);

        $rate->delete();
        $response = new ApiResponse(200,__('api.rate deleted successfully'),[]);
        return $response->send();
    }
}

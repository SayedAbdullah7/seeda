<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Models\Rate;
use App\Response\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EditRateController extends Controller
{
    public function index(Request $request){
        $data = $request->validated();
        $rate = Rate::find($request->id);
        unset($data["id"]);
        $rate->update($data);
        $response = new ApiResponse(200,__('api.rate updated successfully'),[]);
        return $response->send();
    }
}

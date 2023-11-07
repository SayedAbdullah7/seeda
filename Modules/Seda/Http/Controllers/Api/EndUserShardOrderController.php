<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Enums\orderStatus;
use App\Models\Order;
use App\Models\orderPassenger;
use App\Response\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EndUserShardOrderController extends Controller
{

    public function index(Request $request)
    {
        $data = $request->all();
        $end = orderPassenger::where("user_id",$data["user_id"])->where("order_id",$data["order_id"])->first();
        $end->update(["status"=>orderStatus::end]);
        return (new ApiResponse(200,__("api.OrderEndedSuccessfully"),[]))->send();
    }

}

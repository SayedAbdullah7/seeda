<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Enums\orderStatus;
use App\Models\Order;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;

class getLiveTrackigController extends Controller
{

    public function index(Request $request)
    {
        $request->validate([
            "order_id"=>"required|".Rule::exists("orders","id")->where("appKey","520"),
        ]);

        $order = Order::find($request->order_id);
        if ($order->status == orderStatus::start){
            $Locations= $order->locations->toArray();
            $fromLocation= $order->locations->where('type','from')->first();

            return view('seda::LiveTrackin',compact("order","fromLocation","Locations"));
        }else{
            abort(404);
        }

    }

}

<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Http\Resources\historyOrderCollection;
use App\Http\Resources\historyOrderResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Response\ApiResponse;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class historyOrdersController extends Controller
{
    public function index(Request $request){
        $query = Order::query();

        $query = $query->where('user_id',auth()->id());

        $query = $query->orWhere(function($q){
            return $q->whereHas('driver',function($q){
                return $q->where('user_id',auth()->user()->id);
            });
        });

        if ($request->has("date")){
            $data = Carbon::parse($request->date)->toDateString();
//            $query = $query->where(DB::raw("DATE(created_at) = '".$data."'"));
            $query = $query->whereDate("created_at","=",$data);
        }

        $query = $query->with(["locations",'OrderDetails']);

        $order = $query->paginate(request()->per_page??1);

        $order = historyOrderCollection::collection($order);

        $resposne = new ApiResponse(200,__('api.your orders history'),[
            "pages"=>$order->lastPage(),
            'orders'=>$order
        ]);
        return $resposne->send();
    }
}

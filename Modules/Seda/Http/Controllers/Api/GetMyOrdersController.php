<?php
namespace Modules\Seda\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AcceptOrderByDriverRequest;
use App\Models\OrderSentToDriver;
use App\Response\ApiResponse;
use App\Models\Order;
use App\Events\generalEvent;
use App\Http\Resources\OrderResource;
use App\Enums\orderStatus;
use Illuminate\Http\Request;

class GetMyOrdersController extends Controller
{
    public $request;
    public function index( Request $request)
    {
        if ($request->has("order_id")){
            $orders = Order::find($request->order_id);
            $data= new OrderResource($orders);
            $resposne = new ApiResponse(200,__('api.your orders'),[
                'orders'=>$data
            ]);
            return $resposne->send();
        }else{
            $orders = Order::with(['user','driver'])
                ->whereBelongsTo(auth()->user())
                ->orwhere(function($q){
                    return $q->whereHas('driver',function($q){
                        return $q->where('user_id',auth()->user()->id);
                    });
                })
                ->forPage(request()->page,request()->per_page??1)
                ->get()->each(function($record){
                    $record->driverUser= $record->driver->user??"";
                });
            $data= OrderResource::collection($orders);
            $resposne = new ApiResponse(200,__('api.your orders'),[
                'orders'=>$data
            ]);
            return $resposne->send();
        }
    }

}

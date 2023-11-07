<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\LocationRequest;
use App\Http\Resources\LastPlaceResource;
use App\Models\Order;
use App\Response\ApiErrorResponse;
use App\Response\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\locationResource;
use Illuminate\Database\Eloquent\Builder;

class LocationController extends Controller
{

    public function index()
    {
        $locations = auth()->user()->locations->when(request()->type, function ($q) {
            return $q->where('type',request()->type);
        });
        $response = new ApiResponse(200,__('api.Location retrieved successfully'),[
            'locations'=>locationResource::collection($locations)
        ]);

        return $response->send();
    }

    public function fav(){
        $home = auth()->user()->locations->where('type', 'LIKE', "home")->first();
        $work = auth()->user()->locations->where('type', 'LIKE', "work")->first();
        $fav = auth()->user()->locations->where('type', 'LIKE', "fav");

        $response = new ApiResponse(200,__('api.Location retrieved successfully'),[
            'home'=>$home?new locationResource($home):null,
            'work'=>$work?new locationResource($work):null,
            'fav' =>count($fav)>0?locationResource::collection($fav):null,
        ]);

        return $response->send();
    }

    public function getLastPlaces(){
        $order = Order::where("appKey",appKey())->where("user_id",auth()->id())->whereHas("locations", function (Builder $query) {
            return $query->where('type','to');
        })->latest()->limit(10)->get();
        $response = new ApiResponse(200,__('api.Location retrieved successfully'),[
            'lastLocations'=>count($order)>0?LastPlaceResource::collection($order):null,
        ]);

        return $response->send();
    }

    public function store(LocationRequest $request)
    {
        $data = $request->validated();
        if ($request->has('id') && $request->id != null){

            $request->validate([
                'id'=>'exists:locations,id'
            ]);
            unset($data['id']);
            $locations = auth()->user()->locations()->where('id',$request->id);
            if (count($locations->get()) > 0){
                $locations->update($data);
                $response = new ApiResponse(200,__('api.location created or updated successfully'),[]);
            }else{
                $response = new ApiResponse(405,__("api.No location founded or you don't have access on this id"),[]);
            }
        }else{
            unset($data['id']);
            $locations = auth()->user()->locations()->create($data);
            $response = new ApiResponse(200,__('api.location created or updated successfully'),[]);
        }
        return $response->send();
    }


    public function destroy($id)
    {
        $locations = auth()->user()->locations()->whereId($id)->delete();
        if($locations){
            $response = new ApiResponse(200,__('api.location deleted successfully'),[]);
            return $response->send();
        }
        $response = new ApiResponse(405,__("api.don't have right to delete this location "),[]);
        return $response->send();
    }

}

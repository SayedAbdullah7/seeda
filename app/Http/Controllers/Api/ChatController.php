<?php

namespace App\Http\Controllers\Api;

use App\Response\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoomResource;
use App\Http\Requests\Api\LocationRequest;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = auth()->user()->rooms->where('room_id',request()->room_id);
        $resposne = new ApiResponse(200,__('api.successfully'),[
            'locations'=>RoomResource::collection($locations)   
        ]);
        return $resposne->send();
    }

     

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoomRequest $request)
    {
        $room = auth()->user()->rooms()->updateOrCreate(
            [
                'id'=>$request->id
            ],
            $request->validated()
        );
        $resposne = new ApiResponse(200,__('api.successfully'),[]);
        new generalEvent($order,$drivers->toArray(),'you have new message',__("api.you have new order"));

        return $resposne->send();
    }

    
    public function destroy($id)
    {
        $room = auth()->user()->rooms()->whereId($id)->delete();
        $resposne = new ApiResponse(200,__('api.successfully'),[]);
        return $resposne->send();    }
}

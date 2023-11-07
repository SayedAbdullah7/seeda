<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ActiveLocationRequest;
use App\Models\ActiveLocation;
use App\Models\User;
use App\Response\ApiResponse;
use Illuminate\Http\Request;

class ActiveLocationController extends Controller
{
    public function index( ActiveLocationRequest $request)
    {
        $data= $request->all();

        $activeLocations = auth()->user()->Activelocations()->first();

        $data["locationable_type"]=User::class;
        $data["locationable_id"]=auth()->user()->id;

        if ($activeLocations == null ){
            ActiveLocation::create($data);
        }else{
            $activeLocations->update($data);
        }
        auth()->user()->update(["last_activity"=>now()]);
        return
            ( new ApiResponse(200,__('api.ActiveLocation created or updated successfully'),[]))
                ->send();
    }
}

<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Http\Resources\VehiclesResource;
use App\Http\Resources\VehiclesTypeResource;
use App\Models\User;
use App\Response\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Seda\Transformers\UserResource;

class GetProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userResource= new UserResource($user);
        $vh = ($user->Vehicles != null)?new VehiclesResource($user->Vehicles):null;
        return ( new ApiResponse(200,__('api.UserRetrievedSuccessfully'),['user'=>$userResource,"Vehicle"=>$vh]))->send();
    }
}

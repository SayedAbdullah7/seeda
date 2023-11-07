<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Http\Resources\userResource;
use App\Response\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UpdateProfileController extends Controller
{
    public function index(Request $request){
        $data = $request->all();
        $user = auth()->user();

        if ($request->hasFile("image")){
            SaveOrUpdateMedia($user,$request->file("image"),["type"=>"image"]);
        }

        if ($request->has("driver_images")){
            $this->saveDriverImage($user,$request);
        }

        unset($data["image"]);
        unset($data["driver_images"]);
        $user->update($data);

        $userResource= new userResource($user);

        return ( new ApiResponse(200,__('api.profile Updated successfully'),['user'=>$userResource]))
            ->send();
    }

    private function saveDriverImage($user, $request)
    {

        foreach ($request["driver_images"] as $key => $image){
            auth()->user()->medias()->where("type","driver_images")->where("Second_type",$key)->delete();
            SaveMedia($user,$image,["type"=>"driverImages","Second_type"=>$key]);
        }
    }

}

<?php

namespace App\Http\Controllers\Api;

use App\Enums\mediaableEnum;
use App\Enums\mediaTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Medias;
use App\Models\Order;
use App\Models\User;
use App\Models\Vehicles;
use App\Response\ApiResponse;
use App\services\mediaServices;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use function Composer\Autoload\includeFile;

class UploadImageController extends Controller
{
    public function uploadOrderImage(Request $request){
        $request->validate([
            "mediaable_type"=>[new Enum(mediaableEnum::class),"required"],
            "mediaable_id"=>"required|".Rule::exists("orders","id")->where("appKey",appKey()),
            "type"=>["required",new Enum(mediaTypeEnum::class)],
            "image"=>"required|file",
        ]);
        $class = Order::class;

        mediaServices::StoreMediaData($request->image,$request->mediaable_id,$class,env(appKey())."/upload/Order/",$request->type);

        return (new ApiResponse(200,__("api.imageUploadedSuccessfully"),[]))->send();
    }
    public function uploadUserImage(Request $request){
        $request->validate([
            "mediaable_type"=>[new Enum(mediaableEnum::class),"required"],
            "mediaable_id"=>"required|".Rule::exists("users","id")->where("appKey",appKey()),
            "type"=>["required",new Enum(mediaTypeEnum::class)],
            "image"=>"required|file",
        ]);
        $class =  user::class;
        if (appKey() == 527){
            auth()->user()->update([
               "is_approved"=>0,
               "under_review"=>1
            ]);
        }
        Medias::where("mediaable_type",user::class)->where("mediaable_id",$request->mediaable_id)->delete();
//        mediaServices::StoreMediaData($request->image,$request->mediaable_id,$class,env(appKey())."/upload/user/",$request->type);

        return (new ApiResponse(200,__("api.imageUploadedSuccessfully"),[]))->send();
    }

    public function uploadDriverImages(Request $request){
        $request->validate([
            "images"=>"required",
            "images.*"=>"required|array",
            "images.*.image"=>"required|file",
            "images.*.mediaable_type"=>[new Enum(mediaableEnum::class),"required"],
            "images.*.type"=>["required",new Enum(mediaTypeEnum::class)],
            "images.*.Second_type"=>["required",new Enum(mediaTypeEnum::class)],
        ]);
        $user = auth()->user();

        foreach ($request->images as $image){
            $class =  ($image["mediaable_type"] == "user")?user::class:Vehicles::class;
            $mediaable_id = ($image["mediaable_type"] == "user")?$user->id:$user->Vehicles->id;
            mediaServices::StoreMediaDataSecond($image["image"],$mediaable_id,$class,env(appKey())."/upload/".$image['mediaable_type']."/",$image["type"],$image["Second_type"]);
        }

        return (new ApiResponse(200,__("api.imageUploadedSuccessfully"),[]))->send();
    }
}

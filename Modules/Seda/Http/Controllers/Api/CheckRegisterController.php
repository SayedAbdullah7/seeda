<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Http\Resources\Media\MediaResource;
use App\Http\Resources\VehiclesResource;
use App\Response\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Seda\Transformers\UserResource;

class CheckRegisterController extends Controller
{

    public function index(Request $request)
    {
        $user = auth()->user();
        $vh = ($user->Vehicles != null)?new VehiclesResource($user->Vehicles):null;
//        dd($user->Vehicles->medias()->where("type","vehicleLicense")->get());
        if ($this->checkBasicInfo($user)){
            return (new ApiResponse(200,__("api.checkUserCompletion"),[
                "complete"=>0,
                "flags"=>[
                    "basicInfo"=>0,
                    "nationalId"=>0,
                    "driverLicense"=>0,
                    "DriverCriminalRecorder"=>0,
                    "vehicleLicense"=>0,
                    "vehiclePlatNumber"=>0,
                    "vehicleImage"=>0,
                ],
                "data"=>[
                    "basicInfo"=>new UserResource($user),
                    "Vehicle"=>$vh,
                    "nationalId"=>null,
                    "driverLicense"=>null,
                    "DriverCriminalRecorder"=>null,
                    "vehicleLicense"=>null,
                    "vehiclePlatNumber"=>null,
                    "vehicleImage"=>null,
                ]
            ]))->send();
        }
        $userList = ["nationalId", "driverLicense", "DriverCriminalRecorder"];
        $veList   = ["vehicleLicense", "vehiclePlatNumber", "vehicleImage"];

        for ($i=0;count( $userList) > $i ; $i++){
            if ($this->checkUserImagesSteps($user,$userList[$i])){
                return (new ApiResponse(200,__("api.checkUserCompletion"),[
                    "complete"=>0,
                    "flags"=>[
                        "basicInfo"=>1,
                        "nationalId"=>($i==0)?0:1,
                        "driverLicense"=>($i <= 1)?0:1,
                        "DriverCriminalRecorder"=>($i <= 2)?0:1,
                        "vehicleLicense"=>0,
                        "vehiclePlatNumber"=>0,
                        "vehicleImage"=>0,
                    ],
                    "data"=>[
                        "basicInfo"=>new UserResource($user),
                        "Vehicle"=>$vh,
                        "nationalId"=>MediaResource::collection($user->medias()->where("type","nationalId")->get()),
                        "driverLicense"=>MediaResource::collection($user->medias()->where("type","driverLicense")->get()),
                        "DriverCriminalRecorder"=>MediaResource::collection($user->medias()->where("type","DriverCriminalRecorder")->get()),
                        "vehicleLicense"=>MediaResource::collection($user->Vehicles->medias()->where("type","vehicleLicense")->get()),
                        "vehiclePlatNumber"=>MediaResource::collection($user->Vehicles->medias()->where("type","vehiclePlatNumber")->get()),
                        "vehicleImage"=>MediaResource::collection($user->Vehicles->medias()->where("type","vehicleImage")->get()),
                    ]
                ]))->send();
            }
        }

        for ($i=0;count( $veList) > $i ; $i++){
            if ($this->checkVeImagesSteps($user,$veList[$i])){
                return (new ApiResponse(200,__("api.checkUserCompletion"),[
                    "complete"=>0,
                    "flags"=>[
                        "basicInfo"=>1,
                        "nationalId"=>1,
                        "driverLicense"=>1,
                        "DriverCriminalRecorder"=>1,
                        "vehicleLicense"=>($i == 0)?0:1,
                        "vehiclePlatNumber"=>($i <= 1)?0:1,
                        "vehicleImage"=>($i <= 2)?0:1,
                    ],
                    "data"=>[
                        "basicInfo"=>new UserResource($user),
                        "Vehicle"=>$vh,
                        "nationalId"=>MediaResource::collection($user->medias()->where("type","nationalId")->get()),
                        "driverLicense"=>MediaResource::collection($user->medias()->where("type","driverLicense")->get()),
                        "DriverCriminalRecorder"=>MediaResource::collection($user->medias()->where("type","DriverCriminalRecorder")->get()),
                        "vehicleLicense"=>MediaResource::collection($user->Vehicles->medias()->where("type","vehicleLicense")->get()),
                        "vehiclePlatNumber"=>MediaResource::collection($user->Vehicles->medias()->where("type","vehiclePlatNumber")->get()),
                        "vehicleImage"=>MediaResource::collection($user->Vehicles->medias()->where("type","vehicleImage")->get()),
                    ]
                ]))->send();
            }
        }


        return (new ApiResponse(200,__("api.checkUserCompletion"),[
            "complete"=>1,
            "flags"=>[
                "basicInfo"=>1,
                "nationalId"=>1,
                "driverLicense"=>1,
                "DriverCriminalRecorder"=>1,
                "vehicleLicense"=>1,
                "vehiclePlatNumber"=>1,
                "vehicleImage"=>1,
            ],
            "data"=>[
                "basicInfo"=>new UserResource($user),
                "Vehicle"=>$vh,
                "nationalId"=>MediaResource::collection($user->medias()->where("type","nationalId")->get()),
                "driverLicense"=>MediaResource::collection($user->medias()->where("type","driverLicense")->get()),
                "DriverCriminalRecorder"=>MediaResource::collection($user->medias()->where("type","DriverCriminalRecorder")->get()),
                "vehicleLicense"=>MediaResource::collection($user->Vehicles->medias()->where("type","vehicleLicense")->get()),
                "vehiclePlatNumber"=>MediaResource::collection($user->Vehicles->medias()->where("type","vehiclePlatNumber")->get()),
                "vehicleImage"=>MediaResource::collection($user->Vehicles->medias()->where("type","vehicleImage")->get()),
            ]
        ]))->send();
    }

    private function checkBasicInfo($user){
        if ($user->name == null)
            return true;
        return false;
    }

    private function checkUserImagesSteps($user , $key){

        $count = $user->medias()->where("type",$key)->get()->count();
        if (0 < $count && $count <= 2 )
            return false;
        return true;
    }

    private function checkVeImagesSteps($user , $key){

        $count = $user->Vehicles->medias()->where("type",$key)->get()->count();
        if (0 < $count && $count <= 2 )
            return false;
        return true;
    }


}

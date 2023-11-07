<?php

namespace Modules\Seda\Http\Controllers\Api;

use App\Http\Resources\PromoCodeResource;
use App\Models\PromoCodesUser;
use App\Response\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UserPromoCodesController extends Controller
{
    public function index(){
        $promoCodes = PromoCodesUser::with("PromoCode")->where("status",0)->where("user_id",auth()->id())->get();
        return (new ApiResponse(200,__("api.get user PromoCodes"),[
            "promoCodes"=>PromoCodeResource::collection($promoCodes)
        ]))->send();
    }
}

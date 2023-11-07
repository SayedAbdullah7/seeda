<?php

namespace App\Http\Controllers\Api\Promo;

use App\Http\Controllers\Controller;
use App\Response\ApiResponse;
use App\services\PromoCodeService;
use Illuminate\Http\Request;

class UserAddPromoCodesController extends Controller
{
    public function index($code){
        $resp = PromoCodeService::AddPromoToUser($code);

        if ($resp == 1)
            return (new ApiResponse(200,__("api.promo add successfully"),[]))->send();
        elseif ($resp == 2)
            return (new ApiResponse(404,__("api.promo not founded"),[]))->send();
        elseif ($resp == 3)
            return (new ApiResponse(405,__("api.promo added Before"),[]))->send();
        elseif ($resp == 0)
            return (new ApiResponse(406,__("api.promo not valid"),[]))->send();


        return (new ApiResponse(400,__("api.unknown Error"),[]))->send();
    }
}

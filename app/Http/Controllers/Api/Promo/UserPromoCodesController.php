<?php

namespace App\Http\Controllers\Api\Promo;

use App\Http\Controllers\Controller;
use App\Http\Resources\PromoCodeResource;
use App\Models\PromoCodesUser;
use App\Response\ApiResponse;
use Illuminate\Http\Request;

class UserPromoCodesController extends Controller
{
   public function index(){
       return GeneralApiFactory("UserPromoCodesController",Request::class);
   }

}

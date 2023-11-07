<?php

namespace App\Http\Controllers\Api\ShardeRideOrder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class getMySherdOrderController extends Controller
{
    public function index(){
        return GeneralApiFactory('getMySherdOrderController',Request::class);
    }
}

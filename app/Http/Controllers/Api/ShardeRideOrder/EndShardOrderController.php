<?php

namespace App\Http\Controllers\Api\ShardeRideOrder;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\EndShardOrderRequest;

class EndShardOrderController extends Controller
{
    public function index(){
        return GeneralApiFactory('EndShardOrderController',EndShardOrderRequest::class);
    }
}

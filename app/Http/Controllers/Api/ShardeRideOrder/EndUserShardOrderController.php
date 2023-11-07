<?php

namespace App\Http\Controllers\Api\ShardeRideOrder;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\EndUserShardOrderRequest;

class EndUserShardOrderController extends Controller
{
    public function index(){
        return GeneralApiFactory('EndUserShardOrderController',EndUserShardOrderRequest::class);
    }
}

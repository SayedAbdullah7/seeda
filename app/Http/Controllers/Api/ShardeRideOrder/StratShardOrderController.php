<?php

namespace App\Http\Controllers\Api\ShardeRideOrder;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\EndUserShardOrderRequest;
use App\Http\Requests\Api\StratShardOrderRequest;

class StratShardOrderController extends Controller
{
    public function index(){
        return GeneralApiFactory('StratShardOrderController',StratShardOrderRequest::class);
    }
}

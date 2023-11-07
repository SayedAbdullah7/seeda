<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ShowOrderRequest;

class ShowOrderController extends Controller
{
    public function index(){
        return GeneralApiFactory('ShowOrderController',ShowOrderRequest::class);
    }
}

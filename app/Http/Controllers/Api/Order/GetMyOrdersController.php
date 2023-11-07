<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetMyOrderRequest;

class GetMyOrdersController extends Controller
{
    public function index()
    {
        return GeneralApiFactory('GetMyOrdersController',GetMyOrderRequest::class);
    }
}

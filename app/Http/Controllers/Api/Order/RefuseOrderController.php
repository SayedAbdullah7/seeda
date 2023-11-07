<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RefuseOrderRequest;

class RefuseOrderController extends Controller
{
    public function index()
    {
        return GeneralApiFactory('RefuseOrderController',RefuseOrderRequest::class);
    }
}

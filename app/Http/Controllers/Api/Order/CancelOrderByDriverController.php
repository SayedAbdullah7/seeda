<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CancelOrderByUserRequest;

class CancelOrderByDriverController extends Controller
{
    public function index()
    {
        return GeneralApiFactory('CancelOrderByDriverController',CancelOrderByUserRequest::class);
    }
}

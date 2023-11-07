<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CancelOrderByUserRequest;
use App\Http\Requests\Api\AcceptOrderByDriverRequest;

class CancelOrderByUserController extends Controller
{
    public function index()
    {
        return GeneralApiFactory('CancelOrderByUserController',CancelOrderByUserRequest::class);
    }
}

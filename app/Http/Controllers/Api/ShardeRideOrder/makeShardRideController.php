<?php

namespace App\Http\Controllers\Api\ShardeRideOrder;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\makeShardRideRequest;

class makeShardRideController extends Controller
{
    public function index()
    {
        return GeneralApiFactory('MakeShardRideController',makeShardRideRequest::class);
    }
}

<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DriverArrivedController extends Controller
{
    public function index(){
        return GeneralApiFactory("DriverArrivedController",Request::class);
    }
}

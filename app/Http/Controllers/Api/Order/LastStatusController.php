<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LastStatusController extends Controller
{
    public function index(){
        return GeneralApiFactory('LastStatusController',Request::class);
    }
}

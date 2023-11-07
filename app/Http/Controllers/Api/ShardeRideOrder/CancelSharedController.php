<?php

namespace App\Http\Controllers\Api\ShardeRideOrder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CancelSharedController extends Controller
{
    public function index(){
        return GeneralApiFactory('CancelSharedController',Request::class);
    }
}

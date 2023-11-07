<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SendOfferByDriverController extends Controller
{
    public function index()
    {
        return GeneralApiFactory('SendOfferByDriverController',Request::class);
    }
}

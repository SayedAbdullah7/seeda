<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AcceptOfferByUserController extends Controller
{
    public function index()
    {
        return GeneralApiFactory('AcceptOfferByUserController',Request::class);
    }
}

<?php

namespace App\Http\Controllers\Api\Rate;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetRateRequest;

class GetRateController  extends Controller 
{
    public function index()
    {
        return GeneralApiFactory('GetRateController',GetRateRequest::class);
    }
}

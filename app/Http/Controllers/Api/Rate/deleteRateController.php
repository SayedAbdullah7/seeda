<?php

namespace App\Http\Controllers\Api\Rate;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DeleteRateRequest;

class deleteRateController extends Controller
{
    public function index()
    {
        return GeneralApiFactory('deleteRateController',DeleteRateRequest::class);
    }
}

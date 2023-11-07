<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\verifyOtpRequest;
use Illuminate\Http\Request;

class verfiySocailOtpController extends Controller
{
    public function index()
    {
        return GeneralApiFactory('verfiySocailOtpeController',verifyOtpRequest::class);
    }
}

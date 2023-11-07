<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\verifyPhoneRequest;
use Illuminate\Http\Request;

class verfiySocailPhoneController extends Controller
{
    public function index()
    {
        return GeneralApiFactory('verfiySocailPhoneController',verifyPhoneRequest::class);
    }
}

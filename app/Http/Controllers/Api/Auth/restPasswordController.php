<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class restPasswordController extends Controller
{
    public function index()
    {
        return GeneralApiFactory('RestPasswordController',Request::class);
    }

}

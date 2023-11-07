<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;

class updateProfileController extends Controller
{
    public function index()
    {
        return GeneralApiFactory('UpdateProfileController',UpdateProfileRequest::class);
    }
}

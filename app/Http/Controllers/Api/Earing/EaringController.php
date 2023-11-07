<?php

namespace App\Http\Controllers\Api\Earing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EaringController extends Controller
{
    public function index(){
        return GeneralApiFactory("EaringController",Request::class);
    }
}

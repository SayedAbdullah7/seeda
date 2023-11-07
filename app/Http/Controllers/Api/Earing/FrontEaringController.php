<?php

namespace App\Http\Controllers\Api\Earing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FrontEaringController extends Controller
{
    public function index(){
        return GeneralApiFactory("FrontEaringController",Request::class);
    }
}

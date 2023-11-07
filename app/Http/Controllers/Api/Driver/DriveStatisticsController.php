<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DriveStatisticsController extends Controller
{
    public function index(){
        return GeneralApiFactory("DriveStatisticsController",Request::class);
    }
}

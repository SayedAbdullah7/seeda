<?php

namespace App\Http\Controllers\Api\Reservation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VehicleFinishReservationController extends Controller
{
    public function index(){
        return GeneralApiFactory("VehicleFinishReservationController",Request::class);
    }
}

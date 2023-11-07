<?php

namespace App\Http\Controllers\Api\Card;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CardSaveController extends Controller
{
    public function index(){
        return GeneralApiFactory("CardSaveController",Request::class);
    }
}

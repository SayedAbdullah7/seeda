<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Chat\GetChatMassRequest;

class getChatMassageController extends Controller
{
    public function index(){
        return GeneralApiFactory('GetChatMassageController',GetChatMassRequest::class);
    }
}

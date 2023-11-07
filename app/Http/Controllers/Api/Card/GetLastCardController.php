<?php

namespace App\Http\Controllers\Api\Card;

use App\Http\Controllers\Controller;
use App\Http\Resources\CardResource;
use App\Models\Card;
use App\Response\ApiResponse;
use Illuminate\Http\Request;

class GetLastCardController extends Controller
{
    public function index(){
        $card = Card::where("cardable_id",auth()->id())->latest()->first();
        return (new ApiResponse(200,__("api.Cards Retrieved successfully"),[new CardResource($card)]))->send();

    }
}

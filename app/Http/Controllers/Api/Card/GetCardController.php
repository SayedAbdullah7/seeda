<?php

namespace App\Http\Controllers\Api\Card;

use App\Http\Controllers\Controller;
use App\Http\Resources\CardResource;
use App\Models\Card;
use App\Response\ApiResponse;

class GetCardController extends Controller
{
    public function index(){
        $cards = Card::where("cardable_id",auth()->id())->get();

        return (new ApiResponse(200,__("api.Cards Retrieved successfully"),[CardResource::collection($cards)]))->send();
    }

}

<?php

namespace App\Http\Controllers\Api\Card;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Response\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DeleteCardController extends Controller
{
    public function index(Request $request){
        $request->validate([
           "card_id"=>"required|".Rule::exists('cards',"id")->where("cardable_id",auth()->id())
        ]);

        $card = Card::where("id",$request->card_id)->where("cardable_id",auth()->id())->first();
        if ($card){
            $card->delete();
            return (new ApiResponse(200,__("api.cardDeletedSuccessfully"),[]))->send();
        }
        return (new ApiResponse(406,__("api.cardDeletedSuccessfully"),[]))->send();
    }
}

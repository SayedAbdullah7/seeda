<?php

namespace App\Http\Controllers\Api\Wallet;

use App\Enums\EmployeeTransactionTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\EmployeeTransaction;
use App\Response\ApiResponse;
use App\services\walletService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeAddMoneyController extends Controller
{
    public function index(Request $request){
        $request->validate([
           "user_id"=>"required|".Rule::exists("users","id")->where("appKey",appKey()),
           "amount"=>"required|numeric",
        ]);

        (new walletService())->chargeToAnthoerUser($request->amount,$request->user_id);

        $this->AddTranscation($request->amount,$request->user_id);

        return (new ApiResponse(200,__("api.WalletChargedSuccessfully"),[]))->send();
    }

    private function AddTranscation($amount,$user_id){
        EmployeeTransaction::create([
            "amount"=>$amount,
            "user_id"=>$user_id,
            "type"=>EmployeeTransactionTypeEnum::chargeuserwallet,
            "employee_id"=>auth()->id(),
            "date"=>now()->toDateString(),
            "appKey"=>appKey(),
        ]);
    }
}

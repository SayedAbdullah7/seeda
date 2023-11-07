<?php

namespace App\Http\Controllers\Api\Wallet;

use App\Http\Controllers\Controller;
use App\Models\EmployeeTransaction;
use App\Response\ApiResponse;
use Illuminate\Http\Request;

class GetEmployeeBalanceController extends Controller
{
    public function index(){
        $uncollected = EmployeeTransaction::where("employee_id",auth()->id())->where("is_collected",0)->sum("amount");
        $collected =  EmployeeTransaction::where("employee_id",auth()->id())->where("is_collected",1)->sum("amount");
        $collected_data =  EmployeeTransaction::where("employee_id",auth()->id())->where("is_collected",1)->get();
        $Uncollected_data =  EmployeeTransaction::where("employee_id",auth()->id())->where("is_collected",0)->get();

        return (new ApiResponse(200,__("api.GetEmployeeBalance"),[
            "uncollected"=>$uncollected,
            "collected"=>$collected,
            "collected_data"=>$collected_data,
            "uncollected_data"=>$Uncollected_data
        ]))->send();
    }
}

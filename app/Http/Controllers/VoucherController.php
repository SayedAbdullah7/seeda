<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoucherRequest;
use App\Http\Resources\VoucherResource;
use App\Models\Vouchers;
use App\Response\ApiResponse;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index(){
        $allVoucher = Vouchers::where("appKey",appKey())->get();
        $response=  new ApiResponse(200,__('api.new Coupon deleted'),[
            "Voucher"=>VoucherResource::collection($allVoucher)
        ]);
        return $response->send();
    }

    public function store(VoucherRequest $request){
        $data = $request->validated();
        Vouchers::create($data);
        $response=  new ApiResponse(200,__('api.new Coupon deleted'),[]);
        return $response->send();
    }

    public function update(VoucherRequest $request,Vouchers $vouchers){
        $data = $request->validated();

        $vouchers->update($data);
        $response=  new ApiResponse(200,__('api.new Coupon deleted'),[]);
        return $response->send();
    }

    public function destroy(Vouchers $vouchers){
        $vouchers->delete();
        $response=  new ApiResponse(200,__('api.new Coupon deleted'),[]);
        return $response->send();    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\PromoCodeRequest;
use App\Http\Resources\VoucherResource;
use App\Models\PromoCode;
use App\Response\ApiResponse;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    public function index(){
        $allVoucher = PromoCode::where("appKey",appKey())->get();
        $response=  new ApiResponse(200,__('api.new Coupon deleted'),[
            "Voucher"=>VoucherResource::collection($allVoucher)
        ]);
        return $response->send();
    }

    public function store(PromoCodeRequest $request){
        $data = $request->validated();
        PromoCode::create($data);
        $response=  new ApiResponse(200,__('api.new Coupon deleted'),[]);
        return $response->send();
    }

    public function update(PromoCodeRequest $request, PromoCode $promoCode){
        $data = $request->validated();

        $promoCode->update($data);
        $response=  new ApiResponse(200,__('api.new Coupon deleted'),[]);
        return $response->send();
    }

    public function destroy(PromoCode $promoCode){
        $promoCode->delete();
        $response=  new ApiResponse(200,__('api.new Coupon deleted'),[]);
        return $response->send();
    }
}

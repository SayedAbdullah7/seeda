<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppContentRequest;
use App\Models\AppContent;
use App\Response\ApiResponse;
use Illuminate\Http\Request;

class AppContentController extends Controller
{

    public function create(){

    }

    public function store(AppContentRequest $request){
        $data = $request->validated();
        $privacy = AppContent::create($data);
        return $privacy;
    }

    public function edit(){

    }

    public function update($id, AppContentRequest $request){
        $data = $request->validated();
        $privacy = AppContent::find($id);
        $privacy->update($data);
        return $privacy;
    }

    public function get($key)
    {
        $appContent = AppContent::where("key",$key)->where("AppKey",appKey())->first();

        if (!$appContent)
            return ( new ApiResponse(203,__('api.no AppContent founded'),[]))->send();

        return response()->view("appContent",compact("appContent"));
    }

}

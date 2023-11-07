<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Response\ApiResponse;
use App\services\TrashService;
use Illuminate\Http\Request;

class MoveUserToTrashController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            "id"=>"exists:users,id"
        ]);
        $id = $request->id;
        if (auth()->id() == $id){
            $user = User::find($id);
            TrashService::moveToTrah($user);
            return (new ApiResponse(200,"your account archived successfully!",[]))->send();
        }
        return (new ApiResponse(405,"you don't have right to delete this account",[]))->send();
    }
}

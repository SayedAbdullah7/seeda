<?php

namespace App\Http\Middleware;

use App\Response\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Token
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->currentAccessToken()->abilities[0] == "verify_otp" ||
            Auth::user()->currentAccessToken()->abilities[0] == "register"||
            Auth::user()->currentAccessToken()->abilities[0] == "restPassWord"){
            return (new ApiResponse(401,'This Token used for verify otp or register only',[]))->send();
        }
        return $next($request);
    }
}

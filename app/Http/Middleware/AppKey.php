<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Cookie;
use Config;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Response\ApiResponse;

class AppKey
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

        $appKey= appKey();
        if(!$appKey)
            return (new ApiResponse(403,'appKey required',[]))->send();

        if(env($appKey)==null)
            return (new ApiResponse(422,'wrong appKey',[]))->send();

        if(auth()->check()) {
            if($appKey!= auth()->user()->appKey)
                return (new ApiResponse(401,'wrong appKey',[]))->send();
        }


        return $next($request);

    }
}

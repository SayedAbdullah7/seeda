<?php

namespace App\Http\Middleware;

use App\Response\ApiResponse;
use Closure;
use Illuminate\Http\Request;

class Driver
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse | \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!(auth()->user()->type == "captain")){
            if ((auth()->user()->type == "provider")){
                return $next($request);
            }
            if ((auth()->user()->type == "director")){
                return $next($request);
            }
            return (new ApiResponse(401,'This Token is invalid because  this action specified to  driver only ',[]))->send();
        }
        return $next($request);
    }
}

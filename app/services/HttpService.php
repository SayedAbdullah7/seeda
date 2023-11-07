<?php

namespace App\services;

use Illuminate\Support\Facades\Http;

class HttpService
{
    public static function post($url,$header,$data,$token = null){
        if ($token != null)
            return Http::withToken($token)->withHeaders($header)->post($url,$data);
        return Http::withHeaders($header)->post($url,$data);
    }

    public static function get($url,$header,$data,$token = null){
        if ($token != null)
            return Http::withToken($token)->withHeaders($header)->post($url,$data);
        return Http::withHeaders($header)->post($url,$data);
    }

    public static function put($url,$header,$data,$token = null){
        if ($token != null)
            return Http::withToken($token)->withHeaders($header)->post($url,$data);
        return Http::withHeaders($header)->post($url,$data);
    }
}

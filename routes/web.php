<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get("liveTracking",[\App\Http\Controllers\getLiveTrackigController::class,'index']);
Route::get('/clear-cache', function () {
    return \Artisan::call('optimize:clear');
});

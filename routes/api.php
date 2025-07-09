<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WahanaControllerApi;
use App\Http\Controllers\Api\CustomerControllerApi;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::apiResource('/wahana', WahanaControllerApi::class);
Route::apiResource('/customer', CustomerControllerApi::class);

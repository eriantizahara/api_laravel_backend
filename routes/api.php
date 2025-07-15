<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WahanaControllerApi;
use App\Http\Controllers\Api\CustomerControllerApi;
use App\Http\Controllers\Api\AuthControllerApi;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// routes/api.php

// Route::post('/login', [AuthControllerApi::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
return $request->user();
});



Route::apiResource('/wahana', WahanaControllerApi::class);
Route::apiResource('/customer', CustomerControllerApi::class);

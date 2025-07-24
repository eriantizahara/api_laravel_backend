<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WahanaControllerApi;
use App\Http\Controllers\Api\AuthControllerApi;
use App\Http\Controllers\Api\UserControllerApi;
use App\Http\Controllers\Api\PemesananTiketApiController;



// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


// routes/api.php
Route::post('/login', [AuthControllerApi::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user(); // harus mengembalikan name, email, status, dll.
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/pemesanan', [PemesananTiketApiController::class, 'index']);
    Route::post('/pemesanan', [PemesananTiketApiController::class, 'store']);
    Route::get('/pemesanan/{id}', [PemesananTiketApiController::class, 'show']);
});



Route::apiResource('/wahana', WahanaControllerApi::class);
Route::apiResource('/user', UserControllerApi::class);

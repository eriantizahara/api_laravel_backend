<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardWebController;
use App\Http\Controllers\WahanaWebController;
use App\Http\Controllers\CustomerWebController;
use App\Http\Controllers\UserWebController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('/dashboard', DashboardWebController::class);
Route::resource('/wahanas', WahanaWebController::class);
Route::resource('/customers', CustomerWebController::class);
Route::resource('users', UserWebController::class);

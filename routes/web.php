<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardWebController;
use App\Http\Controllers\WahanaWebController;
use App\Http\Controllers\CustomerWebController;
use App\Http\Controllers\UserWebController;
use App\Http\Controllers\LoginWebController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('/dashboard', DashboardWebController::class);
Route::resource('/wahanas', WahanaWebController::class);
Route::resource('/customers', CustomerWebController::class);
Route::resource('/users', UserWebController::class);

Route::get('/login', [LoginWebController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginWebController::class, 'ceklogin'])->name('login.cek');
Route::post('/logout', [LoginWebController::class, 'logout'])->name('logout');

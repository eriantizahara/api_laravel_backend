<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardWebController;
use App\Http\Controllers\WahanaWebController;
use App\Http\Controllers\UserWebController;
use App\Http\Controllers\LoginWebController;
use App\Http\Controllers\PemesananTiketWebController;
use App\Http\Controllers\LaporanWebController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    // Route::get('/pemesanantikets/create', [PemesananTiketWebController::class, 'create'])->name('pemesanantikets.create');
    Route::resource('/pemesanantikets', PemesananTiketWebController::class);
});

Route::resource('/dashboard', DashboardWebController::class);
Route::resource('/wahanas', WahanaWebController::class);
Route::resource('/users', UserWebController::class);
Route::resource('/pemesanantikets', PemesananTiketWebController::class);
Route::get('/pemesanantikets/{id}/faktur/pdf', [PemesananTiketWebController::class, 'fakturPdf'])->name('pemesanantikets.faktur.pdf');

Route::get('/laporan', [LaporanWebController::class, 'index'])->name('laporan.index');
Route::get('/laporan/cetak', [LaporanWebController::class, 'cetakPDF'])->name('laporan.cetak');
// Route::get('/laporan/statistik', [LaporanWebController::class, 'statistik'])->name('laporan.statistik');



Route::get('/login', [LoginWebController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginWebController::class, 'ceklogin'])->name('login.cek');
Route::post('/logout', [LoginWebController::class, 'logout'])->name('logout');

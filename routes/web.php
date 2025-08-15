<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\guruController;
use App\Http\Controllers\mapelController;
use App\Http\Controllers\jadwalController;
use App\Http\Controllers\absensiController;
Route::get('/', function () {
    return view('index');
})->name('dashboard');

Route::resource('/guru', guruController::class);
Route::resource('/mapel', mapelController::class);
Route::resource('/jadwal', jadwalController::class);
Route::resource('/absensi', AbsensiController::class);
Route::get('/absensi/get-data/{guru_id}', [AbsensiController::class, 'getDataGuru'])->name('absensi.getDataGuru');
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\guruController;
use App\Http\Controllers\mapelController;
use App\Http\Controllers\jadwalController;
use App\Http\Controllers\absensiController;
use App\Http\Controllers\gajiController;
use App\Http\Controllers\gajiansController;
use App\Http\Controllers\JabatanController;
Route::get('/', function () {
    return view('index');
})->name('dashboard');

Route::resource('/jabatan', JabatanController::class);
Route::resource('/guru', guruController::class);
Route::resource('/kelas', KelasController::class);
Route::resource('/mapel', mapelController::class);
Route::resource('/jadwal', jadwalController::class);
Route::resource('/absensi', AbsensiController::class);
Route::resource('/rekap', gajiController::class);
Route::resource('/gaji', gajiansController::class);
Route::get('/absensi/get-data/{guru_id}', [AbsensiController::class, 'getDataGuru'])->name('absensi.getDataGuru');
Route::post('/rekap/filter', [gajiController::class, 'filter'])->name('rekap.filter');
Route::post('/gajians/filter', [gajiansController::class, 'filter'])->name('gajians.filter');
Route::get('/rekap/{guru_id}/detail/{bulan}/{tahun}', [gajiController::class, 'detail'])->name('absensi.detail');


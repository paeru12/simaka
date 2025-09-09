<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\guruController;
use App\Http\Controllers\mapelController;
use App\Http\Controllers\jadwalController;
use App\Http\Controllers\absensiController;
use App\Http\Controllers\absenqrController;
use App\Http\Controllers\gajiController;
use App\Http\Controllers\gajiansController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\QrKelasController;
use App\Http\Controllers\RuanganController;

Route::get('/login', [AdminController::class, 'login'])->name('login');
Route::post('/login', [AdminController::class, 'loginCheck'])->name('loginCheck');
Route::get('/logout', [AdminController::class, 'logout'])->name('logout');
Route::middleware(['auth'])->group(function () {
    Route::get('/', [IndexController::class, 'index'])->name('dashboard');
    Route::resource('/setting', SettingController::class);
    Route::resource('/ruangan', RuanganController::class);
    Route::resource('/jabatan', JabatanController::class);
    Route::resource('/absenqr', absenqrController::class);
    Route::resource('/guru', guruController::class);
    Route::resource('/jurusan', JurusanController::class);
    Route::resource('/kelas', KelasController::class);
    Route::resource('/mapel', mapelController::class);
    Route::resource('/jadwal', jadwalController::class);
    Route::resource('/absensi', AbsensiController::class)->except(['show']);
    Route::resource('/rekap', gajiController::class); 
    Route::resource('/gaji', gajiansController::class);
    Route::resource('admin', AdminController::class);
    Route::get('/profile/{id}/edit', [AdminController::class, 'edits'])->name('admin.edits');
    Route::put('/admin/{id}/update-profile', [AdminController::class, 'updateProfile'])->name('admin.update-profile');
    Route::post('/admin/{id}/change-password', [AdminController::class, 'changePassword'])->name('admin.change-password');
    Route::get('/gaji/slip-gaji/{guru_id}/slip/{bulan}/{tahun}', [gajiansController::class, 'getDataGuru'])->name('gaji.getDataGuru');
    Route::post('/rekap/filter', [gajiController::class, 'filter'])->name('rekap.filter');
    Route::post('/gajians/filter', [gajiansController::class, 'filter'])->name('gajians.filter');
    Route::get('/rekap/{guru_id}/detail/{bulan}/{tahun}', [gajiController::class, 'detail'])->name('rekap.detail');

    Route::post('/absensi/scan', [absenqrController::class, 'scanKelas'])->name('absensi.scan');
    Route::post('/absensi/validate', [absenqrController::class, 'validateScan'])->name('absensi.validate');
    Route::get('/absensi/get-kelas', [AbsensiController::class, 'getKelasByHari'])->name('absensi.getKelas');
    Route::get('/absensi/get-mapel', [AbsensiController::class, 'getMapelByKelas'])->name('absensi.getMapel');
    Route::post('/qr-kelas', [QrKelasController::class, 'store'])->name('qrkelas.store');
});

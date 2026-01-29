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
use App\Http\Controllers\AbsensiHarianController;
use App\Http\Controllers\GajiController;
use App\Http\Controllers\gajiansController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\gajianguruController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\PotonganController;
use App\Http\Controllers\QrGuruController;
use App\Http\Controllers\QrKelasController;
use App\Http\Controllers\rekapController; 
use App\Http\Controllers\RuanganController;

Route::get('/login', [AdminController::class, 'login'])->name('login');
Route::post('/login', [AdminController::class, 'loginCheck'])->name('loginCheck');
Route::get('/logout', [AdminController::class, 'logout'])->name('logout');
Route::middleware(['auth'])->group(function () {
    // sidebar
    Route::get('/', [IndexController::class, 'index'])->name('dashboard');
    Route::resource('/absensi-mapel', absensiController::class)->except(['show']);
    Route::get('/absensi-harian', [AbsensiHarianController::class, 'index'])->name('absensiHarian.ind');
    Route::resource('/jabatan', JabatanController::class);
    Route::resource('/guru', guruController::class);
    Route::resource('/ruangan', RuanganController::class);
    Route::resource('/jurusan', JurusanController::class);
    Route::resource('/kelas', KelasController::class);
    Route::resource('/mapel', mapelController::class);
    Route::resource('/jadwal', jadwalController::class);
    Route::resource('rekap-absensi', rekapController::class)->except(['show']);
    Route::resource('/laporan-gaji', gajiansController::class);
    Route::resource('/setting', SettingController::class);
    Route::resource('administrator', AdminController::class);
    Route::resource('potongan-gaji', PotonganController::class);
    // endsidebar
    Route::delete('/absensih/{id}', [AbsensiHarianController::class, 'destroy'])->name('absensih.destroy');
    Route::post('/absensih/data', [AbsensiHarianController::class, 'data'])->name('absensi.harian.data');
    Route::resource('/absen-qr', absenqrController::class);
    Route::resource('/rekap', GajiController::class)->except(['show']);
    Route::resource('/gaji-guru', gajianguruController::class)->except(['show']);
    Route::get('/laporan-gaji-all', [gajiansController::class, 'indexAll'])->name('gaji.indexAll');
    Route::post('/gaji-filterAll', [gajiansController::class, 'filterAll'])->name('gaji.filterAll'); 
    Route::get('/profile/{id}/edit', [AdminController::class, 'edits'])->name('admin.edits');
    Route::put('/admin/{id}/update-profile', [AdminController::class, 'updateProfile'])->name('admin.update-profile');
    Route::put('/admin/{id}/update-akun', [AdminController::class, 'updateAkun'])->name('admin.update-akun');
    Route::post('/admin/{id}/change-password', [AdminController::class, 'changePassword'])->name('admin.change-password');
    Route::get('/gaji/slip-gaji/{guru_id}/slip/{bulan}/{tahun}', [gajiansController::class, 'getDataGuru'])->name('gaji.getDataGuru');
    Route::get('/rekap-absensi/guru', [GajiController::class, 'dindex'])->name('rekap.dindex');
    Route::post('/rekap/detail', [GajiController::class, 'detailf'])->name('rekap.detailf');
    Route::post('/rekap/total', [GajiController::class, 'rekapTotal'])->name('rekap.total');
    Route::get('/rekap/{guru_id}/detail/{bulan}/{tahun}', [GajiController::class, 'detail'])->name('rekap.detail');

    Route::post('/absensi/scan', [absenqrController::class, 'scanKelas'])->name('absensi.scan');
    Route::post('/absensi/validate', [absenqrController::class, 'validateScan'])->name('absensi.validate');
    Route::get('/absensi/get-kelas', [absensiController::class, 'getKelasByHari'])->name('absensi.getKelas');
    Route::get('/absensi/get-mapel', [absensiController::class, 'getMapelByKelas'])->name('absensi.getMapel');
    Route::get('/absensi-harian', [AbsensiHarianController::class, 'index'])->name('absensi.harian');
    Route::post('/absensi-harian/datang', [AbsensiHarianController::class, 'absenDatang'])->name('absensi.harian.datang');
    Route::post('/absensi-harian/pulang', [AbsensiHarianController::class, 'absenPulang'])->name('absensi.harian.pulang');
    Route::post('/absensi-harian/validasi', [AbsensiHarianController::class, 'validateScanGuru'])->name('absensi.validateGuru');
    Route::post('/absensi-harian/izin', [AbsensiHarianController::class, 'izinStore'])->name('absensi.izinStore');

    Route::get('/detail/{guru_id}/{bulan}/{tahun}', [rekapController::class, 'detail'])->name('rekap.details');
    Route::get('/detail-rekap-data/{guru_id}/{bulan}/{tahun}', [rekapController::class, 'detailrekapdata'])->name('rekap.detailrekapdata');
    Route::get('rekapp/staff', [rekapController::class, 'indexAll'])->name('rekap.indexAll');
    // QR Code
    Route::post('/qr-kelas', [QrKelasController::class, 'store'])->name('qrkelas.store');
    Route::post('/qr-guru', [QrGuruController::class, 'store'])->name('qrguru.store');
    Route::get('/qr-guru/{id}/download', [QrGuruController::class, 'downloadQR'])
        ->name('qr.guru.download');
    Route::get('/qr-kelas/{id}/download', [QrKelasController::class, 'downloadQR'])
        ->name('qr.kelas.download');

    // filter
    Route::post('/absensi/filter', [absensiController::class, 'filter'])->name('absensi.filter');
    Route::post('/jabatan/filter', [JabatanController::class, 'filter'])->name('jabatan.filter');
    Route::post('/guru/filter', [guruController::class, 'filter'])->name('guru.filter');
    Route::post('/ruangan/filter', [RuanganController::class, 'filter'])->name('ruangan.filter');
    Route::post('/jurusan/filter', [JurusanController::class, 'filter'])->name('jurusan.filter');
    Route::post('/kelas/filter', [KelasController::class, 'filter'])->name('kelas.filter');
    Route::post('/mapel/filter', [mapelController::class, 'filter'])->name('mapel.filter');
    Route::post('/jadwal/filter', [jadwalController::class, 'filter'])->name('jadwal.filter');
    Route::post('/administrator/filter', [AdminController::class, 'filter']);
    Route::post('/potongan-gaji/filter', [PotonganController::class, 'filter']);
    Route::post('/rekap/filter', [GajiController::class, 'filter'])->name('rekap.filter');
    Route::post('/gajians/filter', [gajiansController::class, 'filter'])->name('gajians.filter');
    Route::post('/gaji-guru/filter', [gajianguruController::class, 'filter'])->name('gajiGuru.filter');
    Route::post('rekapp/filter', [rekapController::class, 'filter'])->name('rekap-filter');
    Route::post('rekapp/filterAll', [rekapController::class, 'filterAll'])->name('rekap.filterAll');
});

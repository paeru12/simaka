<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Absensi;
use App\Models\AbsensiHarian;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Ruangan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class IndexController extends Controller
{
    function index()
    {
        $guru = Guru::count();
        $kelas = Kelas::count();
        $ruangan = Ruangan::count();
        $mapel = MataPelajaran::count();
        $now = Carbon::now('Asia/Jakarta');
        $absen = AbsensiHarian::where('guru_id', Auth::user()->guru_id)
            ->whereDate('tanggal', Carbon::now('Asia/Jakarta')->toDateString())
            ->orderBy('tanggal','desc')
            ->first();
        if (Auth::user()->jabatan->jabatan == 'admin') {
            $absensi = AbsensiHarian::whereDate('created_at', $now->toDateString())->orderBy('tanggal','desc')->get();
        } else {
            $absensi = AbsensiHarian::where('guru_id', Auth::user()->guru_id)
                ->whereMonth('tanggal', Carbon::now('Asia/Jakarta')->month)
                ->whereYear('tanggal', Carbon::now('Asia/Jakarta')->year)
                ->orderBy('tanggal','desc')
                ->get();
        }
        
        if (Auth::user()->jabatan->jabatan == 'admin') {
            $data = Absensi::orderBy('created_at', 'desc')
            ->whereDate('created_at', $now->toDateString())
            ->get();
        } else {
            $startOfWeek = $now->copy()->startOfWeek();
            $endOfWeek = $now->copy()->endOfWeek();

            $data = Absensi::orderBy('created_at', 'desc')
                ->where('guru_id', Auth::user()->guru_id)
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->get();
        }
        return view('index', compact('guru', 'kelas', 'ruangan', 'mapel', 'data', 'absen', 'absensi'));
    }
}

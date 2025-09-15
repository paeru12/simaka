<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Ruangan;
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
        if (auth()->user()->jabatan->jabatan == 'admin') {
            $data = Absensi::orderBy('created_at', 'desc')
                ->whereDate('created_at', $now->toDateString())
                ->get();
        } else {
            $data = Absensi::orderBy('created_at', 'desc')
                ->where('guru_id', auth()->user()->guru->id)
                ->whereDate('created_at', $now->toDateString())
                ->get();
        }
        return view('index', compact('guru', 'kelas', 'ruangan', 'mapel', 'data'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\User;
use App\Models\Absensi;
use App\Models\MataPelajaran;
use App\Models\Ruangan;
use Illuminate\Support\Carbon;
class IndexController extends Controller
{
    function index()
    {
        $guru = User::count();
        $kelas = Kelas::count();
        $ruangan = Ruangan::count();
        $mapel = MataPelajaran::count();
        $now = Carbon::now('Asia/Jakarta');
        $data = Absensi::orderBy('created_at', 'desc')
            ->whereDate('created_at', $now->toDateString())
            ->get();
        return view('index', compact('guru', 'kelas', 'ruangan', 'mapel', 'data'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\DB;


class GajiController extends Controller
{
    public function index()
    {
        return view('rabsen');
    }


    public function filter(Request $request)
    {
        try {
            $bulan = $request->bulan;
            $tahun = $request->tahun;

            if (auth()->user()->role == 'admin') {
                $data = Absensi::with(['user' => function ($q) {
                    $q->withCount(['jadwals as total_mapel' => function ($query) {
                        $query->select(DB::raw('COUNT(DISTINCT mapel_id)'));
                    }]);
                }])
                    ->selectRaw('user_id,
                SUM(CASE WHEN status = "Hadir" THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status = "Izin" THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN status = "Sakit" THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN status = "Alfa" THEN 1 ELSE 0 END) as alfa')
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->groupBy('user_id')
                    ->get();
            } else {
                $data = Absensi::with(['user' => function ($q) {
                    $q->withCount(['jadwals as total_mapel' => function ($query) {
                        $query->select(DB::raw('COUNT(DISTINCT mapel_id)'));
                    }]);
                }])
                    ->selectRaw('user_id,
                SUM(CASE WHEN status = "Hadir" THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status = "Izin" THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN status = "Sakit" THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN status = "Alfa" THEN 1 ELSE 0 END) as alfa')
                    ->where('user_id', auth()->id())
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->groupBy('user_id')
                    ->get();
            }
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function detail($guru_id, $bulan, $tahun)
    {
        $guru = User::findOrFail($guru_id);

        $absensi = Absensi::with('mataPelajaran')
            ->selectRaw('mapel_id, 
            SUM(CASE WHEN status = "Hadir" THEN 1 ELSE 0 END) as hadir,
            SUM(CASE WHEN status = "Izin" THEN 1 ELSE 0 END) as izin,
            SUM(CASE WHEN status = "Sakit" THEN 1 ELSE 0 END) as sakit,
            SUM(CASE WHEN status = "Alfa" THEN 1 ELSE 0 END) as alfa')
            ->where('user_id', $guru_id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->groupBy('mapel_id')
            ->get();
        // dd($absensi);
        return view('dabsen', compact('guru', 'absensi', 'bulan', 'tahun'));
    }
}

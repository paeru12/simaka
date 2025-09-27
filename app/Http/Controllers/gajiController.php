<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Guru;
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

            if (auth()->user()->jabatan->jabatan == 'admin') {
                $data = Absensi::with(['guru' => function ($q) {
                    $q->withCount(['jadwals as total_mapel' => function ($query) {
                        $query->select(DB::raw('COUNT(DISTINCT mapel_id)'));
                    }]);
                }])
                    ->selectRaw('guru_id,
                SUM(CASE WHEN status = "Hadir" THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status = "Izin" THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN status = "Sakit" THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN status = "Alpha" THEN 1 ELSE 0 END) as alfa')
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->groupBy('guru_id')
                    ->get();
            } else {
                $data = Absensi::with(['guru' => function ($q) {
                    $q->withCount(['jadwals as total_mapel' => function ($query) {
                        $query->select(DB::raw('COUNT(DISTINCT mapel_id)'));
                    }]);
                }])
                    ->selectRaw('guru_id,
                SUM(CASE WHEN status = "Hadir" THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status = "Izin" THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN status = "Sakit" THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN status = "Alpha" THEN 1 ELSE 0 END) as alfa')
                    ->where('guru_id', auth()->user()->guru->id)
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->groupBy('guru_id')
                    ->get();
            }
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function detail($guru_id, $bulan, $tahun)
    {
        $guru = Guru::findOrFail($guru_id);

        $absensi = Absensi::with('mataPelajaran')
            ->selectRaw('mapel_id, 
            SUM(CASE WHEN status = "Hadir" THEN 1 ELSE 0 END) as hadir,
            SUM(CASE WHEN status = "Izin" THEN 1 ELSE 0 END) as izin,
            SUM(CASE WHEN status = "Sakit" THEN 1 ELSE 0 END) as sakit,
            SUM(CASE WHEN status = "Alpha" THEN 1 ELSE 0 END) as alfa')
            ->where('guru_id', $guru_id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->groupBy('mapel_id')
            ->get();
        return view('dabsen', compact('guru', 'absensi', 'bulan', 'tahun'));
    }

    public function dindex()
    {
        $guru = auth()->user()->guru;
        return view('dabrek', compact('guru'));
    }

    public function detailf(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020|max:' . date('Y'),
        ]);

        $guru_id = auth()->user()->guru->id;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $absensi = Absensi::with('mataPelajaran:id,nama_mapel')
            ->selectRaw('mapel_id,
                SUM(CASE WHEN status = "Hadir" THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status = "Izin" THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN status = "Sakit" THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN status = "Alpha" THEN 1 ELSE 0 END) as alfa')
            ->where('guru_id', $guru_id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->groupBy('mapel_id')
            ->get();

        return response()->json($absensi);
    }
}

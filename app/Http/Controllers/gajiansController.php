<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Guru;
use Illuminate\Support\Facades\DB;

class gajiansController extends Controller
{
    function index()
    {
        $guru = Guru::all();
        return view('gaji', compact('guru'));
    }

    public function filter(Request $request) 
    {
        try {
            $bulan = $request->bulan;
            $tahun = $request->tahun;
            if (auth()->user()->jabatan->jabatan == 'admin') {
                $data = DB::table('absensis as a')
                    ->join('gurus as g', 'g.id', '=', 'a.guru_id')
                    ->leftJoin('jabatans as jbt', 'jbt.id', '=', 'g.jabatan_id')
                    ->leftJoin('jadwals as jd', 'jd.id', '=', 'a.jadwal_id')
                    ->leftJoin('mata_pelajarans as m', 'm.id', '=', 'jd.mapel_id')
                    ->whereMonth('a.tanggal', $bulan)
                    ->whereYear('a.tanggal', $tahun)
                    ->select(
                        'g.id as guru_id',
                        'g.nama as nama',
                        'jbt.jabatan',
                        'jbt.gapok',
                        'jbt.tunjangan',
                        DB::raw('COUNT(DISTINCT jd.mapel_id) as total_mapel'),
                        DB::raw('SUM(CASE WHEN a.status = "Hadir" THEN 1 ELSE 0 END) as total_hadir'),
                        DB::raw('SUM(CASE WHEN a.status = "Hadir" THEN IFNULL(m.gaji,0) ELSE 0 END) as gaji_mengajar'),
                        DB::raw('(IFNULL(jbt.gapok,0) + IFNULL(jbt.tunjangan,0) + 
                          SUM(CASE WHEN a.status = "Hadir" THEN IFNULL(m.gaji,0) ELSE 0 END)) as total_gaji')
                    )
                    ->groupBy('g.id', 'g.nama', 'jbt.jabatan', 'jbt.gapok', 'jbt.tunjangan')
                    ->get();
            } else {
                $data = DB::table('absensis as a')
                    ->join('gurus as g', 'g.id', '=', 'a.guru_id')
                    ->leftJoin('jabatans as jbt', 'jbt.id', '=', 'g.jabatan_id')
                    ->leftJoin('jadwals as jd', 'jd.id', '=', 'a.jadwal_id')
                    ->leftJoin('mata_pelajarans as m', 'm.id', '=', 'jd.mapel_id')
                    ->whereMonth('a.tanggal', $bulan)
                    ->whereYear('a.tanggal', $tahun)
                    ->where('a.guru_id', auth()->user()->guru->id)
                    ->select(
                        'g.id as guru_id',
                        'g.nama as nama',
                        'jbt.jabatan',
                        'jbt.gapok',
                        'jbt.tunjangan',
                        DB::raw('COUNT(DISTINCT jd.mapel_id) as total_mapel'),
                        DB::raw('SUM(CASE WHEN a.status = "Hadir" THEN 1 ELSE 0 END) as total_hadir'),
                        DB::raw('SUM(CASE WHEN a.status = "Hadir" THEN IFNULL(m.gaji,0) ELSE 0 END) as gaji_mengajar'),
                        DB::raw('(IFNULL(jbt.gapok,0) + IFNULL(jbt.tunjangan,0) + 
                          SUM(CASE WHEN a.status = "Hadir" THEN IFNULL(m.gaji,0) ELSE 0 END)) as total_gaji')
                    )
                    ->groupBy('g.id', 'g.nama', 'jbt.jabatan', 'jbt.gapok', 'jbt.tunjangan')
                    ->get();
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    function getDataGuru($id, $bulan, $tahun)
    {
        $guru = Guru::find($id);
        $jabatan = Guru::whereHas('Jabatan', function ($q) {
            $q->where('jabatan', 'bendahara');
        })->first();
        $absensi = Absensi::with('mataPelajaran')
            ->selectRaw('mapel_id,
            SUM(CASE WHEN status = "Hadir" THEN 1 ELSE 0 END) as hadir')
            ->where('guru_id', $guru->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->groupBy('mapel_id')
            ->get();

        return view('dgaji', compact('guru', 'absensi', 'bulan', 'tahun', 'jabatan'));
    }
}

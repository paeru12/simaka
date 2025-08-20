<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use Illuminate\Support\Facades\DB;

class gajiansController extends Controller
{
    function index()
    {

        return view('gaji');
    }

    public function filter(Request $request)
    {
        try {
            $bulan = $request->bulan;
            $tahun = $request->tahun;

            $data = DB::table('absensis as a')
                ->join('gurus as g', 'g.id', '=', 'a.guru_id')
                ->leftJoin('jabatans as jbt', 'jbt.id', '=', 'g.jabatan_id')
                ->leftJoin('jadwals as jd', 'jd.guru_id', '=', 'g.id')
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

                    // total gaji per mapel sesuai gaji di tabel mata_pelajarans
                    DB::raw('SUM(CASE WHEN a.status = "Hadir" THEN IFNULL(m.gaji,0) ELSE 0 END) as gaji_mengajar'),

                    // total gaji = gapok + tunjangan + total gaji mengajar
                    DB::raw('(IFNULL(jbt.gapok,0) + IFNULL(jbt.tunjangan,0) + 
                          SUM(CASE WHEN a.status = "Hadir" THEN IFNULL(m.gaji,0) ELSE 0 END)) as total_gaji')
                )
                ->groupBy('g.id', 'g.nama', 'jbt.jabatan', 'jbt.gapok', 'jbt.tunjangan')
                ->get();

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

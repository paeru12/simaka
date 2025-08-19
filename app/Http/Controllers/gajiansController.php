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

        return view('gaji');
    }

    public function filter(Request $request)
    {
        try {
            $bulan = $request->bulan;
            $tahun = $request->tahun;

            $data = Absensi::with(['guru' => function ($q) {
                $q->withCount(['jadwals as total_mapel' => function ($query) {
                    $query->select(DB::raw('COUNT(DISTINCT mapel_id)'));
                }]);
            }])
                ->selectRaw('guru_id,
                SUM(CASE WHEN status = "Hadir" THEN 1 ELSE 0 END) as hadir')
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->groupBy('guru_id')
                ->get();

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

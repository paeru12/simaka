<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Potongan;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class gajianguruController extends Controller
{
    function index()
    {
        return view('gajiGuru');
    }

    public function filter(Request $request)
    {
        try {
            $tahun = $request->tahun ?? date('Y');
            $minggu = (int) (Setting::where('key', 'minggu')->value('value') ?? 4);
            $jp = (int) (Setting::where('key', 'jp')->value('value') ?? 40);
            $totalPotongan = Potongan::sum('jumlah_potongan');
            $settings = Setting::pluck('value', 'key');
            $gajiMengajar = isset($settings['gaji_mengajar'])
                ? preg_replace('/[^0-9]/', '', $settings['gaji_mengajar'])
                : 0;

            $guruId = Auth::user()->guru->id ?? null;
            if (!$guruId) {
                return response()->json([], 200);
            }

            $data = DB::table('gurus as g')
                ->leftJoin('jabatans as jbt', 'jbt.id', '=', 'g.jabatan_id')
                ->leftJoin('absensis as a', function ($join) use ($tahun) {
                    $join->on('a.guru_id', '=', 'g.id')
                        ->whereYear('a.tanggal', $tahun);
                })
                ->select(
                    DB::raw('MONTH(a.tanggal) as bulan_index'),
                    'jbt.nominal_gaji',
                    DB::raw('COUNT(DISTINCT a.mapel_id) as total_mapel'),
                    DB::raw('SUM(CASE WHEN a.status = "Hadir" THEN 1 ELSE 0 END) as total_hadir')
                )
                ->where('g.id', $guruId)
                ->whereNotNull('a.tanggal')
                ->groupBy('bulan_index', 'jbt.nominal_gaji')
                ->orderBy('bulan_index')
                ->get();

            $bulanNames = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember'
            ];

            $result = $data->map(function ($item) use ($minggu, $jp, $totalPotongan, $gajiMengajar, $bulanNames, $guruId) {
                $gapokBulanan = $jp * ($item->nominal_gaji ?? 0) * $minggu;
                $honorHadir = ($item->total_hadir ?? 0) * $gajiMengajar;
                $totalGaji = $gapokBulanan + $honorHadir - $totalPotongan;

                if ($totalGaji < 0) $totalGaji = 0;

                return [
                    'guru_id' => $guruId,
                    'bulan_index' => $item->bulan_index,
                    'bulan' => $bulanNames[$item->bulan_index] ?? '-',
                    'gapok' => $gapokBulanan,
                    'total_mapel' => (int) $item->total_mapel,
                    'total_hadir' => (int) $item->total_hadir,
                    'total_gaji' => $totalGaji,
                ];
            });

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

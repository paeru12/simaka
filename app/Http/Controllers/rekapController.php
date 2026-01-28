<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;
use App\Models\Guru;

class rekapController extends Controller
{
    function index()
    {
        return view('rekapabsend');
    }

    public function filter(Request $request)
    {
        $bulan  = $request->bulan;
        $tahun  = $request->tahun;
        $search = $request->search;

        $rekapHarian = DB::table('absensi_harians')
            ->select(
                'gurus.id as guru_id',
                'gurus.nama',
                'jabatans.jabatan',
                DB::raw("COUNT(CASE WHEN status = 'Hadir' THEN 1 END) as total_hadir_harian"),
                DB::raw("COUNT(CASE WHEN status = 'Izin' THEN 1 END) as total_izin"),
                DB::raw("COUNT(CASE WHEN status = 'Sakit' THEN 1 END) as total_sakit"),
                DB::raw("COUNT(CASE WHEN status = 'Alpha' THEN 1 END) as total_alpha")
            )
            ->join('gurus', 'gurus.id', '=', 'absensi_harians.guru_id')
            ->join('jabatans', 'jabatans.id', '=', 'gurus.jabatan_id')
            ->whereMonth('absensi_harians.tanggal', $bulan)
            ->whereYear('absensi_harians.tanggal', $tahun)
            ->when(
                $search,
                fn($q) =>
                $q->where('gurus.nama', 'like', "%$search%")
            )
            ->groupBy('gurus.id', 'gurus.nama', 'jabatans.jabatan');

        $rekapMapel = DB::table('absensis')
            ->select(
                'gurus.id as guru_id',
                DB::raw("COUNT(CASE WHEN status = 'Hadir' THEN 1 END) as total_hadir_mapel")
            )
            ->join('gurus', 'gurus.id', '=', 'absensis.guru_id')
            ->whereMonth('absensis.tanggal', $bulan)
            ->whereYear('absensis.tanggal', $tahun)
            ->groupBy('gurus.id');

        $totalMapelGuru = DB::table('jadwals')
            ->select(
                'jadwals.guru_id',
                DB::raw('COUNT(jadwals.id) as total_mapel')
            )
            ->groupBy('jadwals.guru_id');

        $data = DB::table(DB::raw("({$rekapHarian->toSql()}) as harian"))
            ->mergeBindings($rekapHarian)

            ->leftJoinSub($rekapMapel, 'mapel', function ($join) {
                $join->on('harian.guru_id', '=', 'mapel.guru_id');
            })

            ->leftJoinSub($totalMapelGuru, 'tm', function ($join) {
                $join->on('harian.guru_id', '=', 'tm.guru_id');
            })

            ->select(
                'harian.guru_id',
                'harian.nama',
                'harian.jabatan',
                'harian.total_hadir_harian',
                'harian.total_izin',
                'harian.total_sakit',
                'harian.total_alpha',

                DB::raw('COALESCE(mapel.total_hadir_mapel, 0) as total_hadir_mapel'),

                DB::raw("
                CASE 
                    WHEN LOWER(harian.jabatan) = 'guru'
                    THEN COALESCE(tm.total_mapel, 0)
                    ELSE '-'
                END as total_mapel
            "),

                DB::raw('
                (harian.total_hadir_harian + COALESCE(mapel.total_hadir_mapel, 0))
                as total_kehadiran
            ')
            )
            ->paginate(10);

        return response()->json($data);
    }

    function detail($guru_id, $bulan, $tahun)
    {
        $guru = Guru::with('jabatan')->find($guru_id);

        $absensiHarian = DB::table('absensi_harians')
            ->where('guru_id', $guru_id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        return view('detailrekapabsend', compact('guru', 'absensiHarian', 'bulan', 'tahun'));
    }

    function detailrekapdata($guru_id, $bulan, $tahun)
    {
        $guru_id = $guru_id;
        $bulan = $bulan;
        $tahun = $tahun;

        $rekapHarian = DB::table('absensi_harians')
            ->selectRaw('
                SUM(CASE WHEN status = "Hadir" THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status = "Izin" THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN status = "Sakit" THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN status = "Alpha" THEN 1 ELSE 0 END) as alfa
            ')
            ->where('guru_id', $guru_id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        $rekap = Absensi::with('mataPelajaran:id,nama_mapel')
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

        return response()->json(['rekap' => $rekap, 'rekapHarian' => $rekapHarian]);
    }

    function indexAll()
    {
        return view('rekaps.index');
    }

    public function filterAll(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        $user = Auth::user();
        $guruId = $user->guru->id ?? null;
        $query = DB::table('absensi_harians')
            ->select(
                DB::raw('MONTH(tanggal) as bulan_index'),
                DB::raw("COUNT(CASE WHEN status = 'Hadir' THEN 1 END) as hadir"),
                DB::raw("COUNT(CASE WHEN status = 'Izin' THEN 1 END) as izin"),
                DB::raw("COUNT(CASE WHEN status = 'Sakit' THEN 1 END) as sakit"),
                DB::raw("COUNT(CASE WHEN status = 'Alpha' THEN 1 END) as alfa")
            )
            ->whereYear('tanggal', $tahun);

        if ($user->jabatan->jabatan !== 'admin') {
            if (is_null($guruId)) {
                return response()->json([], 200);
            }
            $query->where('guru_id', $guruId);
        }

        $rekap = $query
            ->groupBy(DB::raw('MONTH(tanggal)'))
            ->orderBy(DB::raw('MONTH(tanggal)'))
            ->get()
            ->map(function ($item) {
                $namaBulan = date('F', mktime(0, 0, 0, $item->bulan_index, 1));
                return [
                    'bulan_index' => (int) $item->bulan_index,
                    'bulan' => ucfirst($namaBulan),
                    'hadir' => (int) $item->hadir,
                    'izin'  => (int) $item->izin,
                    'sakit' => (int) $item->sakit,
                    'alfa'  => (int) $item->alfa,
                ];
            });

        return response()->json($rekap);
    }
}

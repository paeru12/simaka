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

        // Ambil user login
        $user = Auth::user();

        // Jika user punya relasi guru, dapatkan id-nya (null jika tidak ada)
        $guruId = $user->guru->id ?? null;

        // Query rekap absensi per bulan
        $query = DB::table('absensi_harians')
            ->select(
                DB::raw('MONTH(tanggal) as bulan_index'),
                DB::raw("COUNT(CASE WHEN status = 'Hadir' THEN 1 END) as hadir"),
                DB::raw("COUNT(CASE WHEN status = 'Izin' THEN 1 END) as izin"),
                DB::raw("COUNT(CASE WHEN status = 'Sakit' THEN 1 END) as sakit"),
                DB::raw("COUNT(CASE WHEN status = 'Alpha' THEN 1 END) as alfa")
            )
            ->whereYear('tanggal', $tahun);

        // Kalau bukan admin, batasi hasil ke guru yang login
        if ($user->jabatan->jabatan !== 'admin') {
            if (is_null($guruId)) {
                // user bukan admin tapi tidak punya relasi guru -> tidak boleh lihat data
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


    function filter(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        // Ambil data absensi harian
        $rekapHarian = DB::table('absensi_harians')
            ->select(
                'gurus.id as guru_id',
                'gurus.nama',
                'gurus.jabatan_id',
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
            ->groupBy('gurus.id', 'gurus.nama', 'gurus.jabatan_id', 'jabatans.jabatan');

        // Ambil data absensi mapel (khusus guru)
        $rekapMapel = DB::table('absensis')
            ->select(
                'gurus.id as guru_id',
                DB::raw("COUNT(CASE WHEN status = 'Hadir' THEN 1 END) as total_hadir_mapel")
            )
            ->join('gurus', 'gurus.id', '=', 'absensis.guru_id')
            ->whereMonth('absensis.tanggal', $bulan)
            ->whereYear('absensis.tanggal', $tahun)
            ->groupBy('gurus.id');

        // Gabungkan hasilnya
        $rekapGabungan = DB::table(DB::raw("({$rekapHarian->toSql()}) as harian"))
            ->mergeBindings($rekapHarian)
            ->leftJoinSub($rekapMapel, 'mapel', function ($join) {
                $join->on('harian.guru_id', '=', 'mapel.guru_id');
            })
            ->select(
                'harian.guru_id',
                'harian.nama',
                'harian.jabatan_id',
                'harian.total_hadir_harian',
                'harian.jabatan',
                DB::raw('COALESCE(mapel.total_hadir_mapel, 0) as total_hadir_mapel'),
                'harian.total_izin',
                'harian.total_sakit',
                'harian.total_alpha',
                DB::raw('(COALESCE(harian.total_hadir_harian,0) + COALESCE(mapel.total_hadir_mapel,0)) as total_kehadiran')
            )
            ->get();
        return response()->json($rekapGabungan);
    }
}

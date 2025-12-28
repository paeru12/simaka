<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Potongan;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class gajiansController extends Controller
{
    function index()
    {
        $guru = Guru::all();
        return view('gaji', compact('guru'));
    }
 
    function indexAll()
    {
        return view('gajiall');
    }

    public function filter(Request $request)
    {
        try {
            $settings = Setting::whereIn('key', ['gaji_mengajar', 'minggu', 'jp'])
                ->pluck('value', 'key');

            $gajiMengajar = isset($settings['gaji_mengajar'])
                ? preg_replace('/[^0-9]/', '', $settings['gaji_mengajar'])
                : 0;
            $totalPotongan = Potongan::sum('jumlah_potongan');
            $minggu = isset($settings['minggu'])
                ? (int) $settings['minggu']
                : 4;

            $bulan = $request->bulan;
            $tahun = $request->tahun;

            $query = DB::table('absensis as a')
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
                    'jbt.nominal_gaji',
                    DB::raw('COUNT(DISTINCT jd.mapel_id) as total_mapel'),
                    DB::raw('SUM(CASE WHEN a.status = "Hadir" THEN 1 ELSE 0 END) as total_hadir')
                )
                ->groupBy('g.id', 'g.nama', 'jbt.jabatan', 'jbt.nominal_gaji');

            if (Auth::user()->jabatan->jabatan !== 'admin') {
                $query->where('a.guru_id', Auth::user()->guru->id);
            }

            $data = $query->get();
            $jp = isset($settings['jp']) ? (int) $settings['jp'] : 40;
            $data = $data->map(function ($item) use ($minggu, $gajiMengajar, $jp, $totalPotongan) {
                $gapok = $jp * ($item->nominal_gaji ?? 0) * $minggu;
                $honorMapel = ($item->total_hadir ?? 0) * $gajiMengajar;
                $totalGaji = $gapok + $honorMapel - $totalPotongan;

                return [
                    'guru_id' => $item->guru_id,
                    'nama' => $item->nama,
                    'jabatan' => $item->jabatan ?? '-',
                    'nominal_gaji' => (int)$item->nominal_gaji,
                    'gapok' => $gapok,
                    'honor_mengajar' => $honorMapel,
                    'total_mapel' => $item->total_mapel,
                    'total_hadir' => $item->total_hadir,
                    'total_gaji' => $totalGaji,
                ];
            });

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function filterAll(Request $request)
    {
        try {
            $tahun = $request->tahun;

            $settings = Setting::where('key', 'minggu')->value('value') ?? 4;
            $minggu = (int) $settings;
            $totalPotongan = Potongan::sum('jumlah_potongan');

            $query = DB::table('gurus as g')
                ->leftJoin('jabatans as jbt', 'jbt.id', '=', 'g.jabatan_id')
                ->leftJoin('absensi_harians as a', function ($join) use ($tahun) {
                    $join->on('a.guru_id', '=', 'g.id')
                        ->whereYear('a.tanggal', $tahun);
                })
                ->select(
                    'g.id as guru_id',
                    'g.nama',
                    'jbt.jabatan',
                    'jbt.nominal_gaji',
                    DB::raw('MONTH(a.tanggal) as bulan_index'),
                    DB::raw('SUM(CASE WHEN a.status = "Hadir" THEN 1 ELSE 0 END) as total_hadir')
                )
                ->whereNotNull('a.tanggal')
                ->groupBy('g.id', 'g.nama', 'jbt.jabatan', 'jbt.nominal_gaji', 'bulan_index')
                ->orderBy('bulan_index');

            if (Auth::user()->jabatan->jabatan !== 'admin') {
                $query->where('g.id', Auth::user()->guru->id);
            }

            $data = $query->get();

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

            $result = $data->map(function ($item) use ($minggu, $totalPotongan, $bulanNames) {
                $gapok = 40 * ($item->nominal_gaji ?? 0) * $minggu;
                $totalGaji = $gapok - $totalPotongan;

                return [
                    'guru_id' => $item->guru_id,
                    'nama' => $item->nama,
                    'jabatan' => $item->jabatan ?? '-',
                    'bulan_index' => $item->bulan_index,
                    'bulan' => $bulanNames[$item->bulan_index] ?? '-',
                    'total_hadir' => (int) $item->total_hadir,
                    'total_gaji' => $totalGaji,
                ];
            });

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getDataGuru($id, $bulan, $tahun)
    {
        try {
            $potongan = Potongan::all();
            $guru = Guru::with('jabatan')->findOrFail($id);
            $jabatan = Guru::whereHas('Jabatan', function ($q) {
                $q->where('jabatan', 'bendahara');
            })->first();
            $settings = Setting::whereIn('key', ['gaji_mengajar', 'minggu', 'jp'])
                ->pluck('value', 'key');

            $gajiMengajar = isset($settings['gaji_mengajar'])
                ? preg_replace('/[^0-9]/', '', $settings['gaji_mengajar'])
                : 0;

            $minggu = isset($settings['minggu'])
                ? (int) $settings['minggu']
                : 4;

            $absensi = Absensi::with('mataPelajaran')
                ->selectRaw('mapel_id, SUM(CASE WHEN status = "Hadir" THEN 1 ELSE 0 END) as hadir')
                ->where('guru_id', $guru->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->groupBy('mapel_id')
                ->get();

            $jp = isset($settings['jp']) ? (int) $settings['jp'] : 40;
            $gapok = $jp * ($guru->jabatan->nominal_gaji ?? 0) * $minggu;

            $totalMengajar = $absensi->sum(function ($item) use ($gajiMengajar) {
                return $item->hadir * $gajiMengajar;
            });

            $totalPotongan = $potongan->sum('jumlah_potongan');

            $totalAkhir = $gapok + $totalMengajar - $totalPotongan;

            return view('dgaji', compact(
                'guru',
                'absensi',
                'bulan',
                'tahun',
                'jabatan',
                'potongan',
                'gajiMengajar',
                'gapok',
                'totalMengajar',
                'totalPotongan',
                'totalAkhir'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

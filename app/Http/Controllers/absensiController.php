<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\MataPelajaran;
use App\Models\Jadwal;
use App\Models\AbsensiHarian;
use Illuminate\Support\Carbon;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function index()
    {
        $absen = AbsensiHarian::where('guru_id', Auth::user()->guru_id)
            ->whereDate('tanggal', Carbon::now('Asia/Jakarta')->toDateString())
            ->orderBy('created_at','desc')
            ->first();
        $guruid = Auth::user()->guru_id;

        if (Auth::user()->jabatan->jabatan == 'admin') {
            $absensi = Absensi::with(['guru', 'mataPelajaran', 'jadwal.kelas', 'jadwal.ruangan'])
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get();
        } else {
            $absensi = Absensi::with(['guru', 'mataPelajaran', 'jadwal.kelas', 'jadwal.ruangan'])
                ->where('guru_id', $guruid)
                ->limit(50)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $mapel = MataPelajaran::all();
        $jadwal = Jadwal::with('mataPelajaran', 'kelas')->where('guru_id', Auth::id())->get();
        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        return view('absensi', compact('absensi', 'mapel', 'jadwal', 'hari', 'absen'));
    }
 
    public function filter(Request $request)
    {
        $user = Auth::user();

        $query = Absensi::with([
            'guru',
            'mataPelajaran',
            'jadwal.kelas',
            'jadwal.ruangan'
        ])->orderBy('tanggal', 'desc');

        if ($user->jabatan->jabatan !== 'admin') {
            $query->where('guru_id', $user->guru_id);
        }

        if ($request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('status', 'like', "%$search%")
                    ->orWhere('tanggal', 'like', "%$search%")
                    ->orWhereHas(
                        'guru',
                        fn($g) =>
                        $g->where('nama', 'like', "%$search%")
                    )
                    ->orWhereHas(
                        'mataPelajaran',
                        fn($m) =>
                        $m->where('nama_mapel', 'like', "%$search%")
                    );
            });
        }

        if ($request->bulan && $request->tahun) {
            $query->whereMonth('tanggal', $request->bulan)
                ->whereYear('tanggal', $request->tahun);
        }

        $data = $query->paginate(10);

        return response()->json($data);
    }


    public function getKelasByHari(Request $request)
    {
        try {
            $hari = $request->hari;
            $guruid = Auth::user()->guru_id;
            $jadwal = Jadwal::with('kelas')
                ->where('hari', $hari)
                ->when(Auth::user()->jabatan->jabatan != 'admin', function ($query) use ($guruid) {
                    $query->where('guru_id', $guruid);
                })
                ->get()
                ->unique('kelas_id');

            return response()->json($jadwal->values());
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function getMapelByKelas(Request $request)
    {
        $hari = $request->hari;
        $kelasId = $request->kelas_id;
        $guruid = Auth::user()->guru_id;
        $jadwal = Jadwal::with('mataPelajaran')
            ->where('hari', $hari)
            ->where('kelas_id', $kelasId)
            ->when(Auth::user()->role != 'admin', function ($query) use ($guruid) {
                $query->where('guru_id', $guruid);
            })
            ->get();

        return response()->json($jadwal);
    }


    private function uploadFoto($file)
    {
        $manager = new ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
        $resized = $manager->read($file->getPathname())->toJpeg(60);
        $imageName = time() . '_' . uniqid() . '.webp';
        $savePath = 'uploads/bukti/' . $imageName;
        if (!file_exists(public_path('uploads/bukti'))) {
            mkdir(public_path('uploads/bukti'), 0777, true);
        }
        file_put_contents(public_path($savePath), (string) $resized);
        return $savePath;
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'jadwal_id' => 'required|exists:jadwals,id',
                'mapel_id' => 'required|exists:mata_pelajarans,id',
                'tanggal' => 'required|date',
                'status' => 'required|in:Hadir,Izin,Sakit,Alpha',
                'keterangan' => 'nullable|string|max:255',
                'foto' => 'required|image|max:2048',
            ]);
            $validated['guru_id'] = Auth::user()->guru_id;
            $validated['jam_absen'] = Carbon::now('Asia/Jakarta')->format('H:i');
            $alreadyExists = Absensi::where('guru_id', $validated['guru_id'])
                ->where('mapel_id', $validated['mapel_id'])
                ->where('jadwal_id', $validated['jadwal_id'])
                ->whereDate('tanggal', $validated['tanggal'])
                ->exists();

            if ($alreadyExists) {
                return response()->json([
                    'success' => false,
                    'message' => "Anda sudah mengisi absensi untuk mata pelajaran ini pada tanggal {$validated['tanggal']}.",
                ], 409);
            }
            if ($request->hasFile('foto')) {
                $validated['foto'] = $this->uploadFoto($request->file('foto'));
            }
            Absensi::create($validated);

            return response()->json([
                'success' => true,
                'message' => "Absensi created successfully",
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => "Error creating absensi: " . $th->getMessage(),
            ]);
        }
    }

    function destroy($id)
    {
        try {
            $absensi = Absensi::findOrFail($id);
            if ($absensi->foto && $absensi->foto !== 'assets/img/blank.jpg' && file_exists(public_path($absensi->foto))) {
                unlink(public_path($absensi->foto));
            }
            $absensi->delete();

            return response()->json([
                'success' => true,
                'message' => "Absensi deleted successfully",
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => "Error deleting absensi: " . $th->getMessage(),
            ]);
        }
    }
}

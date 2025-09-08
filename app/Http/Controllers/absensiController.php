<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\MataPelajaran;
use App\Models\Jadwal;
use Intervention\Image\ImageManager;

class AbsensiController extends Controller
{
    public function index()
    {
        if (auth()->user()->role == 'admin') {
            $absensi = Absensi::with(['user', 'mataPelajaran', 'jadwal.kelas'])->orderBy('created_at', 'desc')->get();
        } else {
            $absensi = Absensi::with(['user', 'mataPelajaran', 'jadwal.kelas'])->where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();
        }
        $mapel = MataPelajaran::all();
        $jadwal = Jadwal::with('mataPelajaran', 'kelas')->where('user_id', auth()->id())->get();
        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        // dd( $jadwal);
        return view('absensi', compact('absensi', 'mapel', 'jadwal', 'hari'));
    }

    public function getKelasByHari(Request $request)
    {
        try {
            $hari = $request->hari;
            $jadwal = Jadwal::with('kelas')
                ->where('user_id', auth()->id())
                ->where('hari', $hari)
                ->get();

            return response()->json($jadwal);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }


    public function getMapelByKelas(Request $request)
    {
        $kelasId = $request->kelas_id;
        $jadwal = Jadwal::with('mataPelajaran')
            ->where('user_id', auth()->id())
            ->where('kelas_id', $kelasId)
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
                'jam_absen' => 'required|date_format:H:i',
                'status' => 'required|in:Hadir,Izin,Sakit,Alpha',
                'keterangan' => 'nullable|string|max:255',
                'foto' => 'required|image|max:2048',
            ]);
            $validated['user_id'] = auth()->id();

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
}

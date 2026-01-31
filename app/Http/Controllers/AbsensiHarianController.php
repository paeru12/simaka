<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsensiHarian;
use App\Models\QrGuru;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Intervention\Image\ImageManager;

class AbsensiHarianController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $absen = AbsensiHarian::where('guru_id', $user->guru_id)
            ->whereDate('tanggal', Carbon::now('Asia/Jakarta'))
            ->orderBy('created_at', 'desc')
            ->first();

        return view('absenh', compact('absen'));
    }

    public function data(Request $request)
    {
        $user = Auth::user();

        $query = AbsensiHarian::with('guru', 'guru.jabatan')
            ->orderBy('tanggal', 'desc');

        if ($user->jabatan->jabatan !== 'admin') {
            $query->where('guru_id', $user->guru_id);
        }

        if ($request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('status', 'like', "%$search%")
                    ->orWhere('tanggal', 'like', "%$search%")
                    ->orWhereHas('guru', function ($g) use ($search) {
                        $g->where('nama', 'like', "%$search%");
                    });
            });
        }

        if ($request->bulan && $request->tahun) {
            $query->whereMonth('tanggal', $request->bulan)
                ->whereYear('tanggal', $request->tahun);
        }

        $data = $query->paginate(10);

        return response()->json($data);
    }

    public function izinStore(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'status'  => 'required|in:Izin,Sakit',
            'foto'    => 'required|image|max:2048',
            'keterangan' => 'required'
        ]);
        $user = Auth::user();

        try {
            $cekDuplikat = AbsensiHarian::where('guru_id', $user->guru_id)
                ->whereDate('tanggal', $request->tanggal)
                ->first();

            if ($cekDuplikat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Absensi pada tanggal ini sudah ada.'
                ], 422);
            }
            if ($request->hasFile('foto')) {
                $fotoPath = $this->uploadFoto($request->file('foto'));
            }

            AbsensiHarian::create([
                'guru_id' => $user->guru_id,
                'tanggal' => $request->tanggal,
                'status'  => $request->status,
                'foto'    => $fotoPath,
                'keterangan'    => $request->keterangan,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Absensi izin berhasil dikirim.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan absensi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function validateScanGuru(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'ip_lan' => 'required|string'
        ]);

        // IP prefix sekolah
        $ipPrefix = Setting::where('key', 'alamat_ip')->value('value');

        // Validasi IP LAN (TRUE, karena dari browser)
        if (!str_starts_with($request->ip_lan, $ipPrefix)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda harus terhubung ke WiFi sekolah!'
            ], 403);
        }

        // ---- VALIDASI QR ----
        $qr = QrGuru::where('token', $request->token)
            ->where('aktif', 1)
            ->with('guru')
            ->first();

        if (!$qr) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR Guru tidak valid atau tidak aktif'
            ], 422);
        }

        if (Auth::user()->guru_id !== $qr->guru_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR ini bukan milik Anda'
            ], 403);
        }

        return response()->json([
            'status' => 'ok',
            'guru' => [
                'id' => $qr->guru->id,
                'nama' => $qr->guru->nama,
                'nip' => $qr->guru->nip,
            ]
        ]);
    }


    public function absenDatang(Request $request)
    {
        if (!$request->qr_token) {
            return response()->json(['status' => 'error', 'message' => 'QR tidak terbaca!'], 422);
        }

        if (!$request->foto) {
            return response()->json(['status' => 'error', 'message' => 'Foto wajib diambil!'], 422);
        }

        $userId = Auth::user()->guru_id;
        $today = now()->toDateString();

        $absensi = AbsensiHarian::firstOrNew([
            'guru_id' => $userId,
            'tanggal' => $today
        ]);

        if ($absensi->jam_datang) {
            return response()->json(['status' => 'error', 'message' => 'Anda sudah absen datang hari ini!'], 409);
        }

        // Simpan foto
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.jpg';
            $path = 'uploads/absensi_harian/' . $filename;
            $file->move(public_path('uploads/absensi_harian'), $filename);
            $absensi->foto = $path;
        } elseif (str_starts_with($request->foto, 'data:image')) {
            $raw = base64_decode(explode(',', $request->foto)[1]);
            $filename = time() . '_' . uniqid() . '.jpg';
            $path = 'uploads/absensi_harian/' . $filename;
            file_put_contents(public_path($path), $raw);
            $absensi->foto = $path;
        }

        $absensi->jam_datang = now()->toTimeString();
        $absensi->status = 'Hadir';
        $absensi->save();

        return response()->json(['status' => 'success', 'message' => 'Absen datang berhasil!']);
    }


    public function absenPulang(Request $request)
    {
        $today = now()->toDateString();

        $absensi = AbsensiHarian::where('guru_id', Auth::user()->guru_id)
            ->where('tanggal', $today)
            ->first();

        if (!$absensi || !$absensi->jam_datang) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum absen datang'
            ]);
        }

        if ($absensi->jam_pulang) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah absen pulang'
            ]);
        }

        $absensi->jam_pulang = now()->toTimeString();
        $absensi->save();

        return response()->json([
            'success' => true,
            'message' => 'Absen pulang berhasil!'
        ]);
    }

    function destroy($id)
    {
        try {
            $absensi = AbsensiHarian::findOrFail($id);
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

    private function getDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    private function uploadFoto($file)
    {
        $manager = new ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
        $resized = $manager->read($file->getPathname())->toJpeg(60);
        $imageName = time() . '_' . uniqid() . '.webp';
        $savePath = 'uploads/absensi_harian/' . $imageName;
        if (!file_exists(public_path('uploads/absensi_harian'))) {
            mkdir(public_path('uploads/absensi_harian'), 0777, true);
        }
        file_put_contents(public_path($savePath), (string) $resized);
        return $savePath;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsensiHarian;
use App\Models\QrGuru;
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
            ->first();

        return view('absenh', compact('absen'));
    }

    public function data(Request $request)
    {
        $user = Auth::user();

        $query = AbsensiHarian::with('guru', 'guru.jabatan')
            ->orderBy('tanggal', 'desc');

        // 🔐 role
        if ($user->jabatan->jabatan !== 'admin') {
            $query->where('guru_id', $user->guru_id);
        }

        // 🔍 search
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

        // 📅 filter bulan & tahun
        if ($request->bulan && $request->tahun) {
            $query->whereMonth('tanggal', $request->bulan)
                ->whereYear('tanggal', $request->tahun);
        }

        // 📄 pagination
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

    public function absenDatang(Request $request)
    {
        try {
            $today = now()->toDateString();
            $userId = Auth::user()->guru_id;

            // Validasi minimal
            if (!$request->qr_token) {
                return response()->json(['status' => 'error', 'message' => 'QR Code tidak terdeteksi!'], 422);
            }

            if (!$request->foto) {
                return response()->json(['status' => 'error', 'message' => 'Foto bukti wajib diambil!'], 422);
            }

            // Cek absensi existing
            $absensi = AbsensiHarian::firstOrNew([
                'guru_id' => $userId,
                'tanggal' => $today,
            ]);

            if ($absensi->jam_datang) {
                return response()->json(['status' => 'error', 'message' => 'Anda sudah absen datang hari ini!'], 409);
            }

            // Simpan foto (base64 to file)
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time() . '_' . uniqid() . '.jpg';
                $path = 'uploads/absensi_harian/' . $filename;
                $file->move(public_path('uploads/absensi_harian'), $filename);
                $absensi->foto = $path;
            } else {
                // Handle jika dikirim dalam bentuk base64 (dari kamera)
                $image = $request->foto;
                if (str_starts_with($image, 'data:image')) {
                    $image = str_replace('data:image/jpeg;base64,', '', $image);
                    $image = str_replace(' ', '+', $image);
                    $filename = time() . '_' . uniqid() . '.jpg';
                    $path = 'uploads/absensi_harian/' . $filename;
                    if (!file_exists(public_path('uploads/absensi_harian'))) {
                        mkdir(public_path('uploads/absensi_harian'), 0777, true);
                    }
                    file_put_contents(public_path($path), base64_decode($image));
                    $absensi->foto = $path;
                }
            }

            $absensi->jam_datang = now()->toTimeString();
            $absensi->lokasi = $request->lokasi;
            $absensi->status = 'Hadir';
            $absensi->save();

            return response()->json(['status' => 'success', 'message' => 'Absen datang berhasil dicatat!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Kesalahan: ' . $e->getMessage()], 500);
        }
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

    public function validateScanGuru(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $user = Auth::user();
        $now = \Carbon\Carbon::now('Asia/Jakarta');
        $tanggal = $now->toDateString();

        // Cari QR Guru
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

        // Validasi kepemilikan QR sesuai user login
        if ($user->guru_id !== $qr->guru_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR ini bukan milik Anda'
            ], 403);
        }

        // Cek apakah sudah ada absensi hari ini
        $absensi = \App\Models\AbsensiHarian::where('guru_id', $qr->guru_id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if ($absensi && $absensi->jam_datang && $absensi->jam_pulang) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Absensi datang dan pulang sudah tercatat hari ini.'
            ], 409);
        }

        // Tentukan status absensi berikutnya
        $next = 'datang';
        if ($absensi && $absensi->jam_datang && !$absensi->jam_pulang) {
            $next = 'pulang';
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'QR Guru valid',
            'guru' => [
                'id' => $qr->guru->id,
                'nama' => $qr->guru->nama,
                'nip' => $qr->guru->nip,
            ],
            'absensi' => [
                'tanggal' => $tanggal,
                'next_action' => $next
            ]
        ]);
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
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\QueryException;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\QrKelas;
use Intervention\Image\ImageManager;

class AbsensisController extends Controller
{
    public function scanKelas(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'foto'  => 'required|image|max:4096',
            'lat'   => 'nullable|numeric',
            'lng'   => 'nullable|numeric',
        ]);

        $user = auth()->user(); // pastikan middleware auth sudah aktif
        $now = Carbon::now('Asia/Jakarta');
        $tanggal = $now->toDateString();
        $time = $now->format('H:i:s');

        // 1) Resolve token -> kelas_id (contoh)
        $qr = QrKelas::where('token', $request->token)->where('aktif', 1)->first();
        if (!$qr) {
            return response()->json(['message' => 'QR tidak valid atau tidak aktif'], 422);
        }

        // 2) Cari jadwal aktif untuk kelas sekarang
        $hari = $now->isoFormat('dddd'); // contoh: 'Senin' (sesuaikan format jadwal)
        $jadwal = Jadwal::where('kelas_id', $qr->kelas_id)
            ->where('hari', $hari)
            ->where('jam_mulai', '<=', $time)
            ->where('jam_selesai', '>=', $time)
            ->first();

        if (!$jadwal) {
            return response()->json(['message' => 'Tidak ada jadwal aktif untuk kelas ini sekarang.'], 422);
        }

        // 3) cek pengganti (opsional) - contoh sederhana
        $guruIdAktif = $jadwal->user_id;
        // cek tabel jadwal_pengganti disini jika ada...

        // 4) validasi user adalah guru yang dijadwalkan
        if ($user->id !== $guruIdAktif) {
            return response()->json(['message' => 'Anda bukan guru yang dijadwalkan pada pertemuan ini.'], 403);
        }

        // 5) Cek dulu (fast check) apakah sudah absen
        $exists = Absensi::where('jadwal_id', $jadwal->id)
            ->whereDate('tanggal', $tanggal)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Absensi untuk jadwal ini sudah tercatat.'], 409);
        }

        // 6) Simpan foto dulu
        if ($request->hasFile('foto')) {
            $fotoPath = $this->uploadFoto($request->file('foto'));
        }

        // 7) Insert dengan transaksi & tangani duplicate key (race condition)
        DB::beginTransaction();
        try {
            $absensi = Absensi::create([
                'id' => (string) Str::uuid(),
                'jadwal_id' => $jadwal->id,
                'mapel_id' => $jadwal->mapel_id,
                'user_id' => $user->id,
                'tanggal' => $tanggal,
                'jam_absen' => $now->format('H:i:s'),
                'status' => $this->hitungStatus($now, $jadwal->jam_selesai), // fungsi bantu, contoh di bawah
                'keterangan' => null,
                'foto' => $fotoPath,
            ]);

            DB::commit();

            return response()->json(['message' => 'Absensi tersimpan', 'data' => $absensi], 201);
        } catch (QueryException $e) {
            DB::rollBack();

            // MySQL duplicate entry error code = 1062
            $isDuplicate = isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062;

            if ($isDuplicate) {
                // Ambil record yang sudah ada untuk ditampilkan (opsional)
                $existing = Absensi::where('jadwal_id', $jadwal->id)
                    ->whereDate('tanggal', $tanggal)
                    ->first();

                return response()->json(['message' => 'Absensi sudah tercatat (race condition terdeteksi).', 'data' => $existing], 409);
            }

            // error lain -> lempar ulang atau log
            throw $e;
        }
    }

    private function hitungStatus($nowCarbon, $jamSelesaiString)
    {
        $jamSelesai = Carbon::createFromFormat('H:i:s', $jamSelesaiString, 'Asia/Jakarta');
        $toleransiMenit = 10;
        return $nowCarbon->lte($jamSelesai->copy()->addMinutes($toleransiMenit)) ? 'Hadir' : 'Terlambat';
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

    public function validateScan(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $user = auth()->user();
        $now = Carbon::now('Asia/Jakarta');
        $tanggal = $now->toDateString();
        $time = $now->format('H:i:s');

        $qr = QrKelas::where('token', $request->token)->where('aktif', 1)->first();
        if (!$qr) {
            return response()->json(['status' => 'error', 'message' => 'QR tidak valid atau tidak aktif'], 422);
        }

        $hari = $now->isoFormat('dddd');
        $jadwal = Jadwal::where('kelas_id', $qr->kelas_id)
            ->where('hari', $hari)
            ->where('jam_mulai', '<=', $time)
            ->where('jam_selesai', '>=', $time)
            ->first();

        if (!$jadwal) {
            return response()->json(['status' => 'error', 'message' => 'Tidak ada jadwal aktif untuk kelas ini sekarang.'], 422);
        }

        if ($user->id !== $jadwal->user_id) {
            return response()->json(['status' => 'error', 'message' => 'Anda bukan guru yang dijadwalkan pada pertemuan ini.'], 403);
        }

        $exists = Absensi::where('jadwal_id', $jadwal->id)->whereDate('tanggal', $tanggal)->exists();
        if ($exists) {
            return response()->json(['status' => 'warning', 'message' => 'Absensi untuk jadwal ini sudah tercatat.'], 409);
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'Jadwal valid',
            'jadwal' => [
                'mapel' => $jadwal->mataPelajaran->nama_mapel,
                'kelas' => $jadwal->kelas->kelas . $jadwal->kelas->rombel,
                'jam_mulai' => $jadwal->jam_mulai,
                'jam_selesai' => $jadwal->jam_selesai,
            ]
        ]); 
    }
}

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
use App\Models\AbsensiHarian;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Auth;
class absenqrController extends Controller 
{
    function index()
    {
        return view('absensiqr');
    }
    public function scanKelas(Request $request)
    {
        
        $request->validate([
            'token' => 'required|string',
            'foto'  => 'required|image|max:4096',
            'lat'   => 'nullable|numeric',
            'lng'   => 'nullable|numeric',
        ]);

        $user = Auth::user();
        $now = Carbon::now('Asia/Jakarta');
        $tanggal = $now->toDateString();
        $time = $now->format('H:i:s');
        $qr = QrKelas::where('token', $request->token)->where('aktif', 1)->first();
        if (!$qr) {
            return response()->json(['message' => 'QR tidak valid atau tidak aktif'], 422);
        }
        $hari = $now->isoFormat('dddd');
        $jadwal = Jadwal::where('ruangan_id', $qr->ruangan_id)
            ->where('hari', $hari)
            ->where('jam_mulai', '<=', $time)
            ->where('jam_selesai', '>=', $time)
            ->first();

        if (!$jadwal) {
            return response()->json(['message' => 'Tidak ada jadwal aktif untuk ruangan ini sekarang.'], 422);
        }
        $guruIdAktif = $jadwal->guru_id;
        if ($user->guru_id !== $guruIdAktif) {
            return response()->json(['message' => 'Anda bukan guru yang dijadwalkan pada pertemuan ini.'], 403);
        }
        $exists = Absensi::where('jadwal_id', $jadwal->id)
            ->whereDate('tanggal', $tanggal)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Absensi untuk jadwal ini sudah tercatat.'], 409);
        }
        if ($request->hasFile('foto')) {
            $fotoPath = $this->uploadFoto($request->file('foto'));
        }
        DB::beginTransaction();
        try {
            $absensi = Absensi::create([
                'id' => (string) Str::uuid(),
                'jadwal_id' => $jadwal->id,
                'mapel_id' => $jadwal->mapel_id,
                'guru_id' => $user->guru_id,
                'tanggal' => $tanggal,
                'jam_absen' => $now->format('H:i:s'),
                'status' => $this->hitungStatus($now, $jadwal->jam_selesai),
                'keterangan' => null,
                'foto' => $fotoPath,
            ]);
            DB::commit();
            return response()->json(['message' => 'Absensi tersimpan', 'data' => $absensi], 201);
        } catch (QueryException $e) {
            DB::rollBack();
            $isDuplicate = isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062;
            if ($isDuplicate) {
                $existing = Absensi::where('jadwal_id', $jadwal->id)
                    ->whereDate('tanggal', $tanggal)
                    ->first();
                return response()->json(['message' => 'Absensi sudah tercatat (race condition terdeteksi).', 'data' => $existing], 409);
            }
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
        $absen = AbsensiHarian::where('guru_id', Auth::user()->guru_id)
            ->whereDate('tanggal', Carbon::now('Asia/Jakarta')->toDateString())
            ->first();
        if (!$absen) {
            return response()->json(['message' => 'Lakukan absen kehadiran hari ini'], 409);
        }
        
        $request->validate([
            'token' => 'required|string',
        ]);

        $user = Auth::user();
        $now = Carbon::now('Asia/Jakarta');
        $tanggal = $now->toDateString();
        $time = $now->format('H:i:s');

        $qr = QrKelas::where('token', $request->token)->where('aktif', 1)->first();
        if (!$qr) {
            return response()->json(['status' => 'error', 'message' => 'QR tidak valid atau tidak aktif'], 422);
        }

        $hari = $now->isoFormat('dddd');
        $jadwal = Jadwal::where('ruangan_id', $qr->ruangan_id)
            ->where('hari', $hari)
            ->where('jam_mulai', '<=', $time)
            ->where('jam_selesai', '>=', $time)
            ->first();

        if (!$jadwal) {
            return response()->json(['status' => 'error', 'message' => 'Tidak ada jadwal aktif untuk ruangan ini sekarang.'], 422);
        }

        if ($user->guru_id !== $jadwal->guru_id) {
            return response()->json(['status' => 'error', 'message' => 'Anda bukan guru yang dijadwalkan pada pertemuan ini.'], 403);
        }

        $exists = Absensi::where('jadwal_id', $jadwal->id)->whereDate('tanggal', $tanggal)->exists();
        if ($exists) {
            return response()->json(['status' => 'warning', 'message' => 'Absensi untuk jadwal ini sudah tercatat.'], 409);
        }
        $jam_mulai = Carbon::parse($jadwal->jam_mulai)->format('H:i');
        $jam_selesai = Carbon::parse($jadwal->jam_selesai)->format('H:i');

        return response()->json([
            'status' => 'ok',
            'message' => 'Jadwal valid',
            'jadwal' => [
                'mapel' => $jadwal->mataPelajaran->nama_mapel,
                'kelas' => $jadwal->kelas->kelas .' '. $jadwal->kelas->jurusan->nama.' '. $jadwal->kelas->rombel,
                'ruangan' => $jadwal->ruangan->nama,
                'jam_mulai' => $jam_mulai,
                'jam_selesai' => $jam_selesai,
            ]
        ]);
    }
}

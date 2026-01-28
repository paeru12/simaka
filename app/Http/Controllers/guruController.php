<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Guru;
use App\Models\Jabatan;
use App\Models\QrGuru;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\DB;

class guruController extends Controller
{

    public function index()
    {
        $setting = Setting::all();
        $logos = $setting->where('key', 'logo')->first();
        $namas = $setting->where('key', 'nama')->first();
        $logo = $logos->value;
        $nama = $namas->value;
        $jabatan = Jabatan::orderBy('created_at', 'desc')->where('jabatan', '!=', 'admin')->get();
        return response()->view('guru', [
            'jabatan' => $jabatan,
            'logo' => $logo,
            'nama' => $nama,
        ]);
    }

    public function filter(Request $request)
    {
        $search = $request->search;
        $jbt = Jabatan::where('jabatan', 'admin')->first();
        $query = Guru::with(['jabatan', 'qrguru', 'users'])
            ->where('jabatan_id', '!=', $jbt->id)
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->where('nama', 'like', "%$search%")
                ->orWhere('nik', 'like', "%$search%")
                ->orWhere('no_hp', 'like', "%$search%")
                ->orWhereHas('users', function ($q) use ($search) {
                    $q->where('email', 'like', "%$search%");
                })
                ->orWhereHas('jabatan', function ($q) use ($search) {
                    $q->where('jabatan', 'like', "%$search%");
                });
        }

        return response()->json($query->paginate(10));
    }


    function store(Request $request)
    {
        $validated = $request->validate([
            'password' => 'required|min:8',
            'kpassword' => 'required|same:password',
            'jabatan_id' => 'required|exists:jabatans,id',
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:50|unique:gurus,nik',
            'no_hp' => 'required|string|max:20',
            'jk' => 'required|in:L,P',
            'email' => 'required|email|unique:users,email',
            'foto' => 'nullable|image|max:2048',
        ]);
        $exists = Guru::where('nama', $validated['nama'])->exists();
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => "Guru '{$validated['nama']}' sudah ada, tidak bisa ditambahkan lagi."
            ], 422);
        }

        DB::beginTransaction();
        try {
            $fotoPath = $validated['foto'] ?? null;
            if ($request->hasFile('foto')) {
                $fotoPath = $this->uploadFoto($request->file('foto'));
            }

            do {
                $id = (string) Str::uuid();
                $existsGuru = Guru::where('id', $id)->exists();
                $existsUser = User::where('guru_id', $id)->exists();
            } while ($existsGuru || $existsUser);

            Guru::create([
                'id' => $id,
                'jabatan_id' => $validated['jabatan_id'],
                'nama' => $validated['nama'],
                'nik' => $validated['nik'],
                'no_hp' => $validated['no_hp'],
                'jk' => $validated['jk'],
                'foto' => $fotoPath ?? 'assets/img/blank.jpg',
            ]);

            User::create([
                'jabatan_id' => $validated['jabatan_id'],
                'guru_id' => $id,
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'status' => '1',
            ]);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Guru created successfully",
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => "Terjadi kesalahan: " . $th->getMessage(),
            ]);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $guru  = Guru::findOrFail($id);
            $force = $request->boolean('force');

            if ($guru->jadwals()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Guru masih digunakan pada data jadwal.'
                ], 409);
            }

            if ($guru->absensis()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Guru ini memiliki data absensi mapel.'
                ], 409);
            }

            if ($guru->absensi_harian()->exists() && !$force) {
                return response()->json([
                    'success' => false,
                    'need_confirmation' => true,
                    'message' => 'Guru masih memiliki data absensi harian. Jika dilanjutkan, seluruh absensi mapel akan ikut terhapus.'
                ], 409);
            }

            if ($force) {
                foreach ($guru->absensi_harian as $absensi) {
                    if ($absensi->foto && $absensi->foto !== 'assets/img/blank.jpg') {
                        $fotoPath = public_path($absensi->foto);
                        if (file_exists($fotoPath)) {
                            unlink($fotoPath);
                        }
                    }
                    $absensi->delete();
                }
            }

            if ($guru->foto && $guru->foto !== 'assets/img/blank.jpg') {
                $fotoPath = public_path($guru->foto);
                if (file_exists($fotoPath)) {
                    unlink($fotoPath);
                }
            }

            $qr = QrGuru::where('guru_id', $guru->id)->first();
            if ($qr) {
                if ($qr->file && file_exists(public_path($qr->file))) {
                    unlink(public_path($qr->file));
                }
                $qr->delete();
            }

            User::where('guru_id', $guru->id)->delete();

            $guru->delete();

            return response()->json([
                'success' => true,
                'message' => 'Guru dan seluruh data terkait berhasil dihapus'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function uploadFoto($file)
    {
        $manager = new ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
        $resized = $manager->read($file->getPathname())->toJpeg(60);
        $imageName = time() . '_' . uniqid() . '.webp';
        $savePath = 'uploads/' . $imageName;
        if (!file_exists(public_path('uploads'))) {
            mkdir(public_path('uploads'), 0777, true);
        }
        file_put_contents(public_path($savePath), (string) $resized);
        return $savePath;
    }
}

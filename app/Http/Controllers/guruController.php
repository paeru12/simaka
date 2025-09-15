<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Guru;
use App\Models\Jabatan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\DB;

class guruController extends Controller
{

    public function index()
    {
        $jbt = Jabatan::where('jabatan', 'admin')->first();
        $guru = Guru::orderBy('created_at', 'desc')->where('jabatan_id', '!=', $jbt->id)->get();
        $jabatan = Jabatan::orderBy('created_at', 'desc')->where('jabatan', '!=', 'admin')->get();
        return response()->view('guru', [
            'guru' => $guru,
            'jabatan' => $jabatan,
        ]);
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

    function destroy($id)
    {
        try {
            $guru = Guru::findOrFail($id);
            if ($guru->jadwals()->exists() || $guru->absensis()->exists() || $guru->jabatan()->exists() || $guru->users()->exists()) {
                return response()->json(['success' => false, 'message' => 'Tidak bisa menghapus guru yang masih digunakan.']);
            } 
            $user = User::where('guru_id', $id)->first();
            if ($guru->foto && $guru->foto !== 'assets/img/blank.jpg' && file_exists(public_path($guru->foto))) {
                unlink(public_path($guru->foto));
            }
            $guru->delete();
            if ($user) {
                $user->delete();
            }
            return response()->json(['success' => true, 'message' => "Guru berhasil dihapus"]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
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

<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jabatan;
use App\Models\User;
use App\Models\Setting;
use Intervention\Image\ImageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    function login()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        $setting = Setting::all();
        $logos = $setting->where('key', 'logo')->first();
        $namas = $setting->where('key', 'nama')->first();
        $logo = $logos->value;
        $nama = $namas->value;
        return response()->view('login', compact('logo', 'nama'));
    }

    function loginCheck(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->with('errorLogin', 'Email / Password salah!');
    }
    function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function index()
    {
        $jbt = Jabatan::where('jabatan', 'admin')->first();
        $data = User::where('jabatan_id', $jbt->id)->get();
        return response()->view('admin.index', [
            'data' => $data,
            'jabatan' => Jabatan::latest()->get(),
        ]);
    }

    public function edits($id)
    {
        $guru = Guru::findOrFail($id);
        $jabatan = Jabatan::orderBy('created_at', 'desc')->get();
        return response()->view('admin.profile', [
            'data' => $guru,
            'jabatan' => $jabatan,
        ]);
    }

    function store(Request $request)
    {
        $validated = $request->validate([
            'password' => 'required|min:8',
            'kpassword' => 'required|same:password',
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:50|unique:gurus,nik',
            'no_hp' => 'required|string|max:20',
            'jk' => 'required|in:L,P',
            'email' => 'required|email|unique:users,email',
            'foto' => 'nullable|image|max:2048',
        ]);

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
            $jbt = Jabatan::where('jabatan', 'admin')->first();
            Guru::create([
                'id' => $id,
                'jabatan_id' => $jbt->id,
                'nama' => $validated['nama'],
                'nik' => $validated['nik'],
                'no_hp' => $validated['no_hp'],
                'jk' => $validated['jk'],
                'foto' => $fotoPath ?? 'assets/img/blank.jpg',
            ]);

            User::create([
                'jabatan_id' => $jbt->id,
                'guru_id' => $id,
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'status' => '1',
            ]);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Admin created successfully",
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => "Terjadi kesalahan: " . $th->getMessage(),
            ]);
        }
    }

    public function updateProfile(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:50|unique:gurus,nik,' . $id . ',id',
            'no_hp' => 'required|string|max:255',
            'jk' => 'required|string|max:255',
            'foto' => 'nullable|image|max:2048',
        ]);
        $exists = Guru::where('nama', $validated['nama'])->where('id', '!=', $id)->exists();
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => "Guru '{$validated['nama']}' sudah ada, tidak bisa ditambahkan lagi."
            ], 422);
        }
        try {
            $user = Guru::findOrFail($id);

            if ($request->hasFile('foto')) {
                $validated['foto'] = $this->uploadFoto($request->file('foto'));
            }

            $user->update($validated);

            return response()->json(['success' => true, 'message' => 'Profil berhasil diperbarui.']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => "Error Update profil: " . $th->getMessage()]);
        }
    }

    public function updateAkun(Request $request, $id)
    {
        $validated = $request->validate([
            'jabatan_id' => 'nullable|exists:jabatans,id',
            'email' => 'required|string|max:50|unique:users,email,' . $id . ',id',
            'status' => 'nullable|in:1',
        ]);

        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $guru = Guru::findOrFail($user->guru_id);
            if ($request->filled('jabatan_id')) {
                $user->jabatan_id = $request->jabatan_id;
                $guru->jabatan_id = $request->jabatan_id;
                $guru->save();
            }
            $user->email = $validated['email'];
            $user->status = $request->has('status') ? 1 : 0;
            $user->save();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Akun berhasil diperbarui.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => "Error Update Akun: " . $th->getMessage()]);
        }
    }

    public function changePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'password' => 'required',
            'newpassword' => 'required|min:8',
            'renewpassword' => 'required|same:newpassword',
        ]);

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Password lama salah.'], 422);
        }

        $user->update(['password' => Hash::make($request->newpassword)]);

        return response()->json(['success' => true, 'message' => 'Password berhasil diubah.']);
    }

    public function destroy(Request $request, $id)
    {
        try {
            /* ================= CEK ADMIN MINIMAL ================= */

            $jabatanAdmin = Jabatan::where('jabatan', 'admin')->first();

            $jumlahAdmin = User::where('jabatan_id', $jabatanAdmin->id)->count();

            if ($jumlahAdmin <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak bisa menghapus data, minimal harus ada 1 admin.'
                ], 422);
            }

            /* ================= DATA ADMIN ================= */

            $guru = Guru::findOrFail($id);
            $user = User::where('guru_id', $guru->id)->first();

            $force = $request->boolean('force');

            /* ================= NEED CONFIRMATION ================= */

            if ($guru->absensi_harian()->exists() && !$force) {
                return response()->json([
                    'success' => false,
                    'need_confirmation' => true,
                    'message' => 'Admin ini memiliki data absensi harian. Jika dilanjutkan, seluruh absensi harian akan ikut terhapus.'
                ], 409);
            }

            /* ================= FORCE DELETE ================= */

            if ($force) {
                foreach ($guru->absensi_harian as $absensi) {
                    // 🔥 hapus absensi mapel
                    if ($absensi->foto && $absensi->foto !== 'assets/img/blank.jpg') {
                        $fotoPath = public_path($absensi->foto);
                        if (file_exists($fotoPath)) {
                            unlink($fotoPath);
                        }
                    }
                    $absensi->delete();
                }
            }

            /* ================= CLEAN FILE ================= */

            if ($guru->foto && $guru->foto !== 'assets/img/blank.jpg') {
                $fotoPath = public_path($guru->foto);
                if (file_exists($fotoPath)) {
                    unlink($fotoPath);
                }
            }

            /* ================= DELETE DATA ================= */

            if ($user) {
                $user->delete();
            }

            $guru->delete();

            return response()->json([
                'success' => true,
                'message' => 'Admin dan seluruh data terkait berhasil dihapus.'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
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

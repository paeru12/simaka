<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    function login()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return response()->view('login');
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

    //crud
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

    public function index()
    {
        return response()->view('admin.index', [
            'data' => User::where('role', 'admin')->latest()->get(),
            'jabatan' => Jabatan::latest()->get(),
        ]);
    }

    public function edits($id)
    {
        return response()->view('admin.profile', [
            'data' => User::findOrFail($id)
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'password' => 'required|min:8',
                'kpassword' => 'required|same:password',
                'jabatan_id' => 'required|exists:jabatans,id',
                'name' => 'required|string|max:255',
                'no_hp' => 'required|string|max:255',
                'jk' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'foto' => 'nullable|image|max:2048',
            ]);

            if ($request->hasFile('foto')) {
                $validated['foto'] = $this->uploadFoto($request->file('foto'));
            }

            User::create([
                'jabatan_id' => $validated['jabatan_id'],
                'name'       => $validated['name'],
                'email'      => $validated['email'],
                'password'   => Hash::make($validated['password']),
                'no_hp'      => $validated['no_hp'],
                'jk'         => $validated['jk'],
                'foto'       => $validated['foto'] ?? 'assets/img/blank.jpg',
                'role'       => $request->role
            ]);

            return response()->json(['success' => true, 'message' => 'Data berhasil ditambahkan.']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => "Data gagal ditambahkan: " . $th->getMessage()]);
        }
    }

    public function updateProfile(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id . ',id',
            'no_hp' => 'required|string|max:255',
            'jk' => 'required|string|max:255',
            'foto' => 'nullable|image|max:2048',
        ]);

        try {
            $user = User::findOrFail($id);

            if ($request->hasFile('foto')) {
                $validated['foto'] = $this->uploadFoto($request->file('foto'));
            }

            $user->update($validated);

            return response()->json(['success' => true, 'message' => 'Profil berhasil diperbarui.']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => "Error Update profil: " . $th->getMessage()]);
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

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->foto && file_exists(public_path($user->foto))) {
                unlink(public_path($user->foto));
            }

            $user->delete();

            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus data: ' . $e->getMessage()]);
        }
    }
}

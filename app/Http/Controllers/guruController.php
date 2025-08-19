<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;

class guruController extends Controller
{
    function index()
    {
        $guru = Guru::with('jabatan')->orderBy('created_at', 'desc')->get();
        $jabatan = Jabatan::orderBy('created_at', 'asc')->get();
        return response()->view('guru', ['guru' => $guru, 'jabatan' => $jabatan]);
    }

    function store(Request $request)
    {
        $validated = $request->validate([
            'jabatan_id' => 'required|exists:jabatans,id',
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:20',
            'jenis_kelamin' => 'required|string|in:L,P',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'foto' => 'required|image|mimes:jpg,png,webp,jfif,jpeg',
        ]);
        try {
            if ($request->hasFile('foto')) {
                $image = $request->file('foto');
                $manager = new ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
                $resized = $manager->read($image->getPathname())->toJpeg(60);
                $imageName = time() . '_' . uniqid() . '.webp';
                $savePath = 'uploads/' . $imageName;
                file_put_contents(public_path($savePath), (string) $resized);
                $validated['foto'] = $savePath;
            }

            Guru::create($validated);
            return response()->json([
                'success' => true,
                'message' => "Guru created successfully",
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => "Error creating guru: " . $th->getMessage(),
            ]);
        }
    }
}

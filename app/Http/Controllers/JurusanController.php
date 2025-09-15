<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function index()
    {
        $jurusan = Jurusan::orderBy('created_at', 'desc')->get();
        return view('jurusan', compact('jurusan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        try {
            $namas = explode(',', $validated['nama']);
            $namaSudahAda = [];

            foreach ($namas as $n) {
                $n = trim($n);
                $exists = Jurusan::where('nama', $n)->exists();
                if ($exists) {$namaSudahAda[] = $n; continue;}
                Jurusan::create(['nama' => $n]);
            }

            if (!empty($namaSudahAda)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Beberapa jurusan tidak ditambahkan karena sudah ada: ' . implode(', ', $namaSudahAda),
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => "Jurusan berhasil ditambahkan"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    function show($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        return response()->json($jurusan);
    }

    function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255'
        ]);

        $exists = Jurusan::where('nama', $validated['nama'])->where('id', '!=', $id)->exists();
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => "Jurusan '{$validated['nama']}' sudah ada, tidak bisa ditambahkan lagi."
            ], 422);
        }

        try {
            $jurusan = Jurusan::findOrFail($id);
            $jurusan->update($validated);
            return response()->json(['success' => true, 'message' => "Jurusan berhasil diupdate"]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }

    function destroy($id)
    {
        try {
            $jurusan = Jurusan::findOrFail($id);

            if ($jurusan->kelas()->exists()) {
                return response()->json(['success' => false, 'message' => 'Tidak bisa menghapus jurusan yang masih memiliki kelas.']);
            }

            $jurusan->delete();
            return response()->json(['success' => true, 'message' => 'Jurusan berhasil dihapus']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }
}

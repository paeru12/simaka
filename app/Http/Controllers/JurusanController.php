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

    function store(Request $request) {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        try {
            $namas = explode(',', $validated['nama']);

            foreach ($namas as $n) {
                Jurusan::create([
                    'nama'     => trim($n),
                ]);
            }

            return response()->json(['success' => true, 'message' => "Jurusan berhasil ditambahkan"]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
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
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MataPelajaran;

class mapelController extends Controller
{
    function index()
    {
        return view('mapel');
    }

    public function filter(Request $request)
    {
        $query = MataPelajaran::withCount('jadwals')
            ->orderBy('created_at', 'desc');

        if ($request->search) {
            $s = $request->search;

            $query->where('nama_mapel', 'like', "%$s%");
        }

        return response()->json(
            $query->paginate(10)
        );
    }


    function store(Request $request)
    {
        $validated = $request->validate([
            'nama_mapel' => 'required|string|max:255',
        ]);
        $exists = MataPelajaran::where('nama_mapel', $validated['nama_mapel'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => "Nama Mapel sudah ada."
            ], 422);
        }

        try {
            MataPelajaran::create($validated);
            return response()->json(['success' => true, 'message' => "Mapel berhasil ditambahkan"]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }

    function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_mapel' => 'required|string|max:255',
        ]);
        $exists = MataPelajaran::where('nama_mapel', $validated['nama_mapel'])
            ->where('id', '!=', $id)
            ->exists();
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => "Nama Mapel sudah ada."
            ], 422);
        }

        try {
            $mapel = MataPelajaran::findOrFail($id);
            $mapel->update($validated);
            return response()->json(['success' => true, 'message' => "Mapel berhasil diperbarui"]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }

    function destroy($id)
    {
        try {
            $mapel = MataPelajaran::findOrFail($id);
            if ($mapel->jadwals()->exists()) {
                return response()->json(['success' => false, 'message' => 'Tidak bisa menghapus mapel yang sudah digunakan di jadwal.']);
            }
            $mapel->delete();
            return response()->json(['success' => true, 'message' => "Mapel berhasil dihapus"]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }

    function show($id)
    {
        $mapel = MataPelajaran::findOrFail($id);
        return response()->json($mapel);
    }
}

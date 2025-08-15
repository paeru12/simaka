<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MataPelajaran;

class mapelController extends Controller
{
    function index()
    {
        $mapel = MataPelajaran::orderBy('created_at', 'desc')->get();
        return view('mapel', compact('mapel'));
    }

    function store(Request $request)
    {
        $validated = $request->validate([
            'nama_mapel' => 'required|string|max:255',
            'kode_mapel' => 'required|string|max:20',
        ]);

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
            'kode_mapel' => 'required|string|max:20',
        ]);

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

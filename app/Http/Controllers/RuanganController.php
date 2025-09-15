<?php

namespace App\Http\Controllers;

use App\Models\QrKelas;
use Illuminate\Http\Request;
use App\Models\Ruangan;

class RuanganController extends Controller
{
    function index()
    {
        $data = Ruangan::orderBy('created_at', 'asc')->get();
        return view('ruangan', compact('data'));
    }

    function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255'
        ]);
        $exists = Ruangan::where('nama', $validated['nama'])->exists();

        if ($exists && strtolower($validated['nama']) !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => "Ruangan '{$validated['nama']}' sudah ada, tidak bisa ditambahkan lagi."
            ], 422);
        }
        try {
            Ruangan::create($validated);
            return response()->json(['success' => true, 'message' => "Ruangan berhasil ditambahkan"]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }

    function show($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        return response()->json($ruangan);
    }

    function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
        ]);
        $exists = Ruangan::where('nama', $validated['nama'])->where('id', '!=', $id)->exists();
        if ($exists && strtolower($validated['nama']) !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => "Ruangan '{$validated['nama']}' sudah ada, tidak bisa ditambahkan lagi."
            ], 422);
        }
        try {
            $ruangan = Ruangan::findOrFail($id);
            $ruangan->update($validated);
            return response()->json(['success' => true, 'message' => "Ruangan berhasil diperbarui"]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }

    function destroy($id)
    {
        try {
            $ruangan = Ruangan::findOrFail($id);
            $qr = QrKelas::where('ruangan_id', $id)->first();
            if ($ruangan->jadwal()->exists()) {
                return response()->json(['success' => false, 'message' => 'Tidak bisa menghapus ruangan yang sudah digunakan di jadwal.']);
            }
            if ($qr->file && file_exists(public_path($qr->file))) {
                unlink(public_path($qr->file));
            }
            $qr->delete();
            $ruangan->delete();
            return response()->json(['success' => true, 'message' => "Ruangan berhasil dihapus"]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }
}

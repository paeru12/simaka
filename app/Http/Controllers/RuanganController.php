<?php

namespace App\Http\Controllers;

use App\Models\QrKelas;
use Illuminate\Http\Request;
use App\Models\Ruangan;
use App\Models\Setting;

class RuanganController extends Controller
{
    function index()
    {
        $setting = Setting::all();
        $logos = $setting->where('key', 'logo')->first();
        $namas = $setting->where('key', 'nama')->first();
        $logo = $logos->value;
        $nama = $namas->value;
        return view('ruangan', compact('logo', 'nama'));
    }

    public function filter(Request $request)
    {
        $query = Ruangan::with('qrKelas')->orderBy('created_at', 'desc');

        if ($request->search) {
            $s = $request->search;

            $query->where('nama', 'like', "%$s%");
        }

        return response()->json(
            $query->paginate(10)
        );
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
            if ($ruangan->jadwal()->exists()) {
                return response()->json(['success' => false, 'message' => 'Tidak bisa menghapus ruangan yang sudah digunakan di jadwal.']);
            }
            $qr = QrKelas::where('ruangan_id', $id)->first();
            if ($qr) {
                if ($qr->file && file_exists(public_path($qr->file))) {
                    unlink(public_path($qr->file));
                }
                $qr->delete();
            }
            $ruangan->delete();
            return response()->json(['success' => true, 'message' => "Ruangan berhasil dihapus"]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }
}

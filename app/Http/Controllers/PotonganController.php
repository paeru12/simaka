<?php

namespace App\Http\Controllers;

use App\Models\Potongan;
use Illuminate\Http\Request;

class PotonganController extends Controller
{
    function index() {
        $potongans = Potongan::all();
        return view('potongan', compact('potongans'));
    }

    function show($id)
    {
        $potongan = Potongan::findOrFail($id);
        return response()->json($potongan);
    }

    function store(Request $request) {
        $validated = $request->validate([
            'nama_potongan' => 'required|string|max:255',
            'jumlah_potongan' => 'required|numeric',
            'keterangan' => 'nullable|string|max:255',
        ]);

        try {
            Potongan::create($validated);
            return response()->json(['success' => true, 'message' => "Potongan Gaji berhasil ditambahkan"]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }

    function update(Request $request, $id) {
        $potongan = Potongan::findOrFail($id);
        $validated = $request->validate([
            'nama_potongan' => 'required|string|max:255',
            'jumlah_potongan' => 'required|numeric',
            'keterangan' => 'nullable|string|max:255',
        ]);
        try {
            $potongan->update($validated);
            return response()->json(['success' => true, 'message' => "Potongan Gaji berhasil diperbarui"]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }

    function destroy($id) {
        try {
            $potongan = Potongan::findOrFail($id);
            $potongan->delete();
            return response()->json(['success' => true, 'message' => "Potongan Gaji berhasil dihapus"]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruangan;
class RuanganController extends Controller
{
    function index() {
        $data = Ruangan::orderBy('created_at', 'asc')->get();
        return view('ruangan', compact('data'));
    } 

    function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255'
        ]);

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
            $ruangan->delete();
            return response()->json(['success' => true, 'message' => "Ruangan berhasil dihapus"]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }
}

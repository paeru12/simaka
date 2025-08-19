<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jabatan;
class JabatanController extends Controller
{
    function index() {
        $data = Jabatan::orderBy('created_at', 'asc')->get();
        return view('jabatan', compact('data'));
    } 

    function store(Request $request)
    {
        $validated = $request->validate([
            'jabatan' => 'required|string|max:255',
            'gapok' => 'required|string|max:255',
            'tunjangan' => 'required|string|max:255',
        ]);

        try {
            Jabatan::create($validated);
            return response()->json(['success' => true, 'message' => "Jabatan berhasil ditambahkan"]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }

    function show($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        return response()->json($jabatan);
    }

     function update(Request $request, $id)
    {
        $validated = $request->validate([
            'jabatan' => 'required|string|max:255',
            'gapok' => 'required|string|max:255',
            'tunjangan' => 'required|string|max:255',
        ]);

        try {
            $jabatan = Jabatan::findOrFail($id);
            $jabatan->update($validated);
            return response()->json(['success' => true, 'message' => "Jabatan berhasil diperbarui"]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }

    function destroy($id)
    {
        try {
            $jabatan = Jabatan::findOrFail($id);
            $jabatan->delete();
            return response()->json(['success' => true, 'message' => "Jabatan berhasil dihapus"]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    function index()
    {
        $kelas = Kelas::withCount(['jadwal'])->orderBy('created_at', 'desc')->get();
        $jurusan = Jurusan::withCount('kelas')->orderBy('created_at', 'desc')->get();
        $data = Jurusan::all();
        return view('kelas', compact('kelas', 'jurusan', 'data'));
    }

    function store(Request $request)
    {
        $validated = $request->validate([
            'jurusan_id' => 'required|exists:jurusans,id',
            'kelas' => 'required|string|max:255',
            'rombel' => 'required|string|max:255'
        ]);

        try {
            Kelas::create($validated);
            return response()->json(['success' => true, 'message' => "Kelas berhasil ditambahkan"]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }

    function show($id)
    {
        $kelas = Kelas::findOrFail($id);
        return response()->json($kelas);
    }

    function update(Request $request, $id)
    {
        $validated = $request->validate([
            'jurusan_id' => 'required|exists:jurusans,id',
            'kelas' => 'required|string|max:255',
            'rombel' => 'required|string|max:255'
        ]);

        try {
            $kelas = Kelas::findOrFail($id);
            $kelas->update($validated);
            return response()->json(['success' => true, 'message' => "Kelas berhasil diupdate"]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }

    function destroy($id)
    {
        try {
            $kelas = Kelas::findOrFail($id);

            if ($kelas->jadwal()->exists()) {
                return response()->json(['success' => false, 'message' => 'Tidak bisa menghapus kelas yang sudah digunakan di jadwal.']);
            }

            $kelas->delete();
            return response()->json(['success' => true, 'message' => 'Kelas berhasil dihapus']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }


    function storeJurusan(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        try {
            Jurusan::create($validated);
            return response()->json(['success' => true, 'message' => "Jurusan berhasil ditambahkan"]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }
    function showJurusan($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        return response()->json($jurusan);
    }
    function updateJurusan(Request $request, $id)
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

    function destroyJurusan($id)
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

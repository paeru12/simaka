<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MataPelajaran;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;

class jadwalController extends Controller
{
    function index()
    {
        $mapel = MataPelajaran::all();
        $guru = Guru::all();
        $kelas = Kelas::all();
        $jadwal = Jadwal::with(['guru', 'mataPelajaran'])->get();
        return view('jadwal', compact('mapel', 'guru', 'jadwal', 'kelas'));
    }

    function store(Request $request)
    {
        $validated = $request->validate([
            'guru_id' => 'required|exists:gurus,id',
            'mapel_id' => 'required|exists:mata_pelajarans,id',
            'kelas_id' => 'required|exists:kelass,id',
            'hari' => 'required|string|max:10',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai'
        ]);

        try {
            Jadwal::create($validated);
            return response()->json(['success' => true, 'message' => "Jadwal berhasil ditambahkan"]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }

    public function show($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        return response()->json($jadwal);
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'guru_id' => 'required|exists:gurus,id',
                'mapel_id' => 'required|exists:mata_pelajarans,id',
                'hari' => 'required|string|max:10',
                'jam_mulai' => 'required|date_format:H:i',
                'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
                'kelas' => 'required|string|max:10',
            ]);

            $jadwal = Jadwal::findOrFail($id);
            $jadwal->update($validated);

            return response()->json(['success' => true, 'message' => "Jadwal berhasil diperbarui"]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()]);
        }
    }

    public function destroy($id)
    {
        try {
            $jadwal = Jadwal::findOrFail($id);
            $jadwal->delete();
            return response()->json(['success' => true, 'message' => "Jadwal berhasil dihapus"]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }
}

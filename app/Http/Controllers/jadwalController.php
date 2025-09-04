<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MataPelajaran;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Ruangan;
use App\Models\User;

class jadwalController extends Controller
{
    function index()
    {
        $mapel = MataPelajaran::all();
        $guru = User::where('role', 'guru')->latest()->get();
        $kelas = Kelas::all();
        $ruangan = Ruangan::all();
        if (auth()->user()->role == 'admin') {
            $jadwal = Jadwal::with(['user', 'mataPelajaran'])->orderby('hari', 'asc')->get();
        }else{
            $jadwal = Jadwal::with(['user', 'mataPelajaran'])->where('user_id', auth()->user()->id)->orderby('hari', 'asc')->get();
        }
        return view('jadwal', compact('mapel', 'guru', 'jadwal', 'kelas', 'ruangan'));
    }

    function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'ruangan_id' => 'required|exists:ruangans,id',
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
                'user_id' => 'required|exists:users,id',
                'ruangan_id' => 'required|exists:ruangans,id',
                'mapel_id' => 'required|exists:mata_pelajarans,id',
                'kelas_id' => 'required|exists:kelass,id',
                'hari' => 'required|string|max:10',
                'jam_mulai' => 'required|date_format:H:i',
                'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
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

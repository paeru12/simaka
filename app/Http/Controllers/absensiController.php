<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Jadwal;

class AbsensiController extends Controller
{
    public function index()
    {
        $absensi = Absensi::with(['guru', 'mataPelajaran', 'jadwal'])->orderBy('tanggal', 'desc')->get();
        $guru = Guru::all();
        $mapel = MataPelajaran::all();
        $jadwal = Jadwal::all();

        return view('absensi', compact('absensi', 'guru', 'mapel', 'jadwal'));
    }

    public function getDataGuru($guru_id)
    {
        try {
            $jadwal = Jadwal::with('mataPelajaran')->where('guru_id', $guru_id)->get();
            return response()->json([
                'success' => true,
                'jadwal' => $jadwal
            ]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'errors' => $th->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'guru_id' => 'required|exists:gurus,id',
            'mapel_id' => 'required|exists:mata_pelajarans,id',
            'jadwal_id' => 'required|exists:jadwals,id',
            'tanggal' => 'required|date',
            'jam_absen' => 'required|date_format:H:i',
            'status' => 'required|in:Hadir,Izin,Sakit,Alfa',
            'keterangan' => 'nullable|string|max:255',
        ]);
        try {
            Absensi::create($validated);

            return response()->json([
                'success' => true,
                'message' => "Absensi created successfully",
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => "Error creating absensi: " . $th->getMessage(),
            ]);
        }
    }
}

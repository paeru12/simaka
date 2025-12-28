<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MataPelajaran;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Ruangan;
use App\Models\Guru;
use Illuminate\Support\Facades\Auth;

class jadwalController extends Controller
{ 
    function index()
    {
        $auth = Auth::user();
        $mapel = MataPelajaran::all();
        $guru = Guru::whereHas('jabatan', function ($query) {
            $query->where('jabatan', 'guru');
        })->latest()->get();
        $kelas = Kelas::orderBy('kelas', 'asc')->get();
        $ruangan = Ruangan::all();
        if ($auth->jabatan->jabatan == 'admin') {
            $jadwal = Jadwal::with(['guru', 'mataPelajaran'])->orderby('hari', 'asc')->get();
        } else {
            $jadwal = Jadwal::with(['guru', 'mataPelajaran'])->where('guru_id', $auth->guru_id)->orderby('hari', 'asc')->get();
        }
        return view('jadwal', compact('mapel', 'guru', 'jadwal', 'kelas', 'ruangan'));
    }

    function store(Request $request)
    {
        $validated = $request->validate([
            'guru_id' => 'required|exists:gurus,id',
            'ruangan_id' => 'required|exists:ruangans,id',
            'mapel_id' => 'required|exists:mata_pelajarans,id',
            'kelas_id' => 'required|exists:kelass,id',
            'hari' => 'required|string|max:10',
            'total_jam' => 'required|string|max:10',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai'
        ]);

        $bentrokGuru = Jadwal::where('guru_id', $validated['guru_id'])
            ->where('hari', $validated['hari'])
            ->where(function ($q) use ($validated) {
                $q->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                    ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                    ->orWhere(function ($q2) use ($validated) {
                        $q2->where('jam_mulai', '<', $validated['jam_mulai'])
                            ->where('jam_selesai', '>', $validated['jam_selesai']);
                    });
            })
            ->exists();

        if ($bentrokGuru) {
            return response()->json([
                'success' => false,
                'message' => 'Guru ini sudah memiliki jadwal pada jam tersebut.'
            ], 422);
        }

        $bentrokRuangan = Jadwal::where('ruangan_id', $validated['ruangan_id'])
            ->where('hari', $validated['hari'])
            ->where(function ($q) use ($validated) {
                $q->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                    ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                    ->orWhere(function ($q2) use ($validated) {
                        $q2->where('jam_mulai', '<', $validated['jam_mulai'])
                            ->where('jam_selesai', '>', $validated['jam_selesai']);
                    });
            })
            ->exists();

        if ($bentrokRuangan) {
            return response()->json([
                'success' => false,
                'message' => 'Ruangan ini sudah dipakai untuk jadwal lain di jam tersebut.'
            ], 422);
        }

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
                'ruangan_id' => 'required|exists:ruangans,id',
                'mapel_id' => 'required|exists:mata_pelajarans,id',
                'kelas_id' => 'required|exists:kelass,id',
                'hari' => 'required|string|max:10',
                'total_jam' => 'required|string|max:10',
                'jam_mulai' => 'required|date_format:H:i',
                'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            ]);

            $bentrokGuru = Jadwal::where('id', '!=', $id)->where('guru_id', $validated['guru_id'])
                ->where('hari', $validated['hari'])
                ->where(function ($q) use ($validated) {
                    $q->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                        ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                        ->orWhere(function ($q2) use ($validated) {
                            $q2->where('jam_mulai', '<', $validated['jam_mulai'])
                                ->where('jam_selesai', '>', $validated['jam_selesai']);
                        });
                })
                ->exists();

            if ($bentrokGuru) {
                return response()->json([
                    'success' => false,
                    'message' => 'Guru ini sudah memiliki jadwal pada jam tersebut.'
                ], 422);
            }

            $bentrokRuangan = Jadwal::where('id', '!=', $id)->where('ruangan_id', $validated['ruangan_id'])
                ->where('hari', $validated['hari'])
                ->where(function ($q) use ($validated) {
                    $q->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                        ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                        ->orWhere(function ($q2) use ($validated) {
                            $q2->where('jam_mulai', '<', $validated['jam_mulai'])
                                ->where('jam_selesai', '>', $validated['jam_selesai']);
                        });
                })
                ->exists();

            if ($bentrokRuangan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ruangan ini sudah dipakai untuk jadwal lain di jam tersebut.'
                ], 422);
            }

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
            if (method_exists($jadwal, 'absensis') && $jadwal->absensis()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal tidak dapat dihapus karena sudah dipakai pada absensi.'
                ], 422);
            }
            $jadwal->delete();
            return response()->json(['success' => true, 'message' => "Jadwal berhasil dihapus"]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }
}

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
            $rombels = explode(',', $validated['rombel']);
            $duplikat = [];

            foreach ($rombels as $rombel) {
                $rombel = trim($rombel);

                $exists = Kelas::where('jurusan_id', $validated['jurusan_id'])
                    ->where('kelas', $validated['kelas'])
                    ->where('rombel', $rombel)
                    ->exists();

                if ($exists) {
                    $duplikat[] = $rombel;
                    continue;
                }

                Kelas::create([
                    'jurusan_id' => $validated['jurusan_id'],
                    'kelas'      => $validated['kelas'],
                    'rombel'     => $rombel,
                ]);
            }

            if (!empty($duplikat)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Beberapa rombel tidak ditambahkan karena sudah ada: ' . implode(', ', $duplikat)
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil ditambahkan'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
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
        $exists = Kelas::where('jurusan_id', $validated['jurusan_id'])
            ->where('kelas', $validated['kelas'])
            ->where('rombel', $validated['rombel'])
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => "Data kelas dengan kombinasi tersebut sudah ada."
            ], 422);
        }
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
}

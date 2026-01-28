<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jabatan;

class JabatanController extends Controller
{
    function index()
    {
        return view('jabatan');
    }

    public function filter(Request $request)
    {
        $query = Jabatan::orderBy('created_at', 'asc');

        if ($request->search) {
            $s = $request->search;

            $query->where(function ($q) use ($s) {
                $q->where('jabatan', 'like', "%$s%")
                    ->orWhere('nominal_gaji', 'like', "%$s%");
            });
        }

        return response()->json(
            $query->paginate(10)
        );
    }


    function store(Request $request)
    {
        $validated = $request->validate([
            'jabatan' => 'required|string|max:255',
            'nominal_gaji' => 'required|integer',
        ]);

        try {
            $exists = Jabatan::where('jabatan', $validated['jabatan'])->exists();
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => "Jabatan '{$validated['jabatan']}' sudah ada, tidak bisa ditambahkan lagi."
                ], 422);
            }
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
            'jabatan' => 'nullable|string|max:255',
            'nominal_gaji' => 'required|integer',
        ]);

        try {
            $exists = Jabatan::where('jabatan', $validated['jabatan'])->where('id', '!=', $id)->exists();
            if ($exists && strtolower($validated['jabatan']) !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => "Jabatan '{$validated['jabatan']}' sudah ada, tidak bisa ditambahkan lagi."
                ], 422);
            }
            $jabatan = Jabatan::findOrFail($id);

            if (strtolower($jabatan->jabatan) == 'admin') {
                $jabatan->nominal_gaji = $request->nominal_gaji;
            } else {
                $jabatan->jabatan = $request->jabatan;
                $jabatan->nominal_gaji = $request->nominal_gaji;
            }

            $jabatan->save();
            return response()->json(['success' => true, 'message' => "Jabatan berhasil diperbarui"]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }

    function destroy($id)
    {
        try {
            $jabatan = Jabatan::findOrFail($id);
            if (strtolower($jabatan->jabatan) === 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => "Jabatan 'admin' tidak bisa dihapus."
                ], 422);
            }

            if ($jabatan->gurus()->exists()) {
                return response()->json(['success' => false, 'message' => 'Tidak bisa menghapus jabatan yang masih digunakan.']);
            }

            $jabatan->delete();
            return response()->json(['success' => true, 'message' => "Jabatan berhasil dihapus"]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }
}

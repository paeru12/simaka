<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
class KelasController extends Controller
{
    function index() {
        $kelas = Kelas::orderBy('created_at', 'asc')->get();
        return view('kelas', compact('kelas'));
    }

    function store(Request $request)
    {
        $validated = $request->validate([
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
}

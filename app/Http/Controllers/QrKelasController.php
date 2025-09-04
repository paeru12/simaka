<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\QrKelas;

class QrKelasController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'ruangan_id' => 'required|string|exists:ruangans,id'
            ]);

            // cek apakah sudah ada qr untuk ruangan ini
            $qrKelas = QrKelas::where('ruangan_id', $validated['ruangan_id'])->first();
            if ($qrKelas) {
                return response()->json([
                    'success' => true,
                    'message' => 'QR Code sudah ada',
                    'data'    => $qrKelas
                ]);
            }

            $validated['token'] = Str::uuid()->toString();


            // path simpan file
            $fileName = 'qrkelas-' . $validated['ruangan_id'] . '.svg';
            $savePath = 'uploads/qr/' . $fileName;
            $path = public_path($savePath);

            // pastikan folder ada
            if (!File::exists(public_path('uploads/qr'))) {
                File::makeDirectory(public_path('uploads/qr'), 0777, true);
            }

            // generate QR dan simpan
            $image = QrCode::format('svg')
                ->size(300)
                ->errorCorrection('H')
                ->generate($validated['token']);

            File::put($path, $image);
            $validated['file'] = $savePath;
            $qrKelas = QrKelas::create($validated);
            return response()->json([
                'success' => true,
                'message' => 'QR Code ruangan berhasil dibuat',
                'data'    => [
                    'id' => $qrKelas->id,
                    'ruangan_id' => $qrKelas->ruangan_id,
                    'token' => $qrKelas->token,
                    'file' => asset('uploads/qr/' . $fileName)
                ]
            ], 201);
        } catch (\Exception $e) {
            \Log::error("QRKelas store error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat QR Code ruangan',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}

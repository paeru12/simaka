<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\QrGuru;
use App\Models\Guru;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
class QrGuruController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'guru_id' => 'required|string|exists:gurus,id'
            ]);

            $qrGuru = QrGuru::where('guru_id', $validated['guru_id'])->first();
            if ($qrGuru) {
                return response()->json([
                    'success' => true,
                    'message' => 'QR Code sudah ada',
                    'data'    => $qrGuru
                ]);
            }

            $validated['token'] = Str::uuid()->toString();

            $guru = Guru::find($validated['guru_id']);
            $fileName = 'qrguru-' . $guru->nama . '.svg';
            $savePath = 'uploads/qr/' . $fileName;
            $path = public_path($savePath);
            if (!File::exists(public_path('uploads/qr'))) {
                File::makeDirectory(public_path('uploads/qr'), 0777, true);
            }

            $image = QrCode::format('svg')
                ->size(300)
                ->errorCorrection('H')
                ->generate($validated['token']);

            File::put($path, $image);
            $validated['file'] = $savePath;
            $qrGuru = QrGuru::create($validated);
            return response()->json([
                'success' => true,
                'message' => 'QR Code guru berhasil dibuat',
                'data'    => [
                    'id' => $qrGuru->id,
                    'guru_id' => $qrGuru->guru_id,
                    'token' => $qrGuru->token,
                    'file' => asset('uploads/qr/' . $fileName)
                ]
            ], 201);
        } catch (\Exception $e) {
            Log::error("QRGuru store error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat QR Code guru',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function downloadQR($id)
    {
        $data = Guru::findOrFail($id);
        $setting = Setting::all();
        $logos = $setting->where('key', 'logo')->first();
        $namas = $setting->where('key', 'nama')->first();
        $pdf = Pdf::loadView('pdf.qr-guru', [
            'nama' => $data->nama,
            'qrPath' => $data->qrguru->file,
            'logo' => $logos->value,
            'appName' => $namas->value
        ])->setPaper('a5', 'portrait');

        return $pdf->download("qr-guru-{$data->nama}.pdf");
    }
}
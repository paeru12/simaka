<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\QrKelas;
use App\Models\Ruangan; 
use App\Models\Setting; 
use Barryvdh\DomPDF\Facade\Pdf;

class QrKelasController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'ruangan_id' => 'required|string|exists:ruangans,id'
            ]);

            $qrKelas = QrKelas::where('ruangan_id', $validated['ruangan_id'])->first();
            if ($qrKelas) {
                return response()->json([
                    'success' => true,
                    'message' => 'QR Code sudah ada',
                    'data'    => $qrKelas
                ]);
            }

            $validated['token'] = Str::uuid()->toString();

            $ruangan = Ruangan::find($validated['ruangan_id']);
            $fileName = 'qrruangan-' . $ruangan->nama . '.svg';
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
            Log::error("QRKelas store error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat QR Code ruangan',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function downloadQR($id)
    {
        $data = Ruangan::findOrFail($id);
        $setting = Setting::all();
        $logos = $setting->where('key', 'logo')->first();
        $namas = $setting->where('key', 'nama')->first();
        $pdf = Pdf::loadView('pdf.qr-kelas', [
            'nama' => $data->nama,
            'qrPath' => $data->qrKelas->file,
            'logo' => $logos->value,
            'appName' => $namas->value
        ])->setPaper('a5', 'portrait');

        return $pdf->download("qr-kelas-{$data->nama}.pdf");
    }
}

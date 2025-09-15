<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;

class SettingController extends Controller
{
    function index()
    {
        $settings = Setting::orderBy('created_at', 'desc')->get();
        return view('setting', compact('settings'));
    }

    function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|in:logo,nama,kop_surat|unique:settings,key',
            'value' => 'nullable',
        ]);

        try {
            $value = null;
            if ($request->hasFile('value')) {
                $validated['value'] = $this->uploadFoto($request->file('value'));
            } else {
                $validated['value'] = $request->input('value');
            }
            Setting::create($validated);
            return response()->json(['success' => true, 'message' => "Data berhasil ditambahkan"]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }

    public function show($id)
    {
        return response()->json(Setting::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $setting = Setting::findOrFail($id);
        $validated = $request->validate([
            'key'   => 'required|in:logo,nama,kop_surat',
            'value' => 'nullable',
        ]);

        try {
            if ($request->hasFile('value')) {
                if (in_array($setting->key, ['logo', 'kop_surat']) && $setting->value && file_exists(public_path($setting->value))) {
                    unlink(public_path($setting->value));
                }
                $validated['value'] = $this->uploadFoto($request->file('value'));
            } else {
                $validated['value'] = $request->input('value');
            }

            $setting->update($validated);

            return response()->json(['success' => true, 'message' => 'Data berhasil diupdate']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }


    private function uploadFoto($file)
    {
        $manager = new ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
        $resized = $manager->read($file->getPathname())->toJpeg(60);
        $imageName = time() . '_' . uniqid() . '.webp';
        $savePath = 'uploads/' . $imageName;
        if (!file_exists(public_path('uploads'))) {
            mkdir(public_path('uploads'), 0777, true);
        }
        file_put_contents(public_path($savePath), (string) $resized);
        return $savePath;
    }
}

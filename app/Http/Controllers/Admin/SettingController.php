<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;
use App\Models\SiteSetting;
use App\Support\MediaStore;
use App\Support\SettingDefaults;
use App\Support\Settings;

class SettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings.edit', [
            'schema' => SettingDefaults::schema(),
            'values' => Settings::all(),
        ]);
    }

    public function update(SettingRequest $request)
    {
        $current = Settings::all();

        foreach (SettingDefaults::fields() as $key => $field) {
            if ($field['type'] === 'image') {
                $path = $current[$key] ?? null;

                if ($request->boolean('remove_logo') && ! $request->hasFile($key)) {
                    MediaStore::delete($path);
                    $path = null;
                }
                if ($request->hasFile($key)) {
                    $path = MediaStore::replace($request->file($key), $path, 'branding');
                }

                SiteSetting::updateOrCreate(['key' => $key], ['value' => $path]);

                continue;
            }

            SiteSetting::updateOrCreate(['key' => $key], ['value' => $request->input($key)]);
        }

        Settings::forget();

        return redirect()->route('admin.settings.edit')->with('success', 'Pengaturan disimpan.');
    }
}

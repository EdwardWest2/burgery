<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use App\Support\SettingDefaults;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        // firstOrCreate: pengaturan yang sudah diubah admin tidak ditimpa.
        foreach (SettingDefaults::fields() as $key => $field) {
            SiteSetting::firstOrCreate(['key' => $key], ['value' => $field['default']]);
        }
    }
}

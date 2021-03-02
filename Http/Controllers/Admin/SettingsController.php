<?php

namespace Modules\Laralite\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Laralite\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $currentSettings = [];

        try {
            $settings = Settings::where('id', 1);
            $settings = $settings->first();

            if ($settings->settings) {
                $currentSettings = $settings->settings;
            }

        } catch (\Throwable $exception) {
           $currentSettings = [];
        }

        return view('laralite::admin.settings', [
            'settings' => $currentSettings,
        ]);
    }
}

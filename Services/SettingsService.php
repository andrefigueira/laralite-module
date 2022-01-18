<?php

namespace Modules\Laralite\Services;

use Modules\Laralite\Models\Settings;

class SettingsService
{
    public function getCurrency()
    {
        return json_decode($this->getSettings()->settings , true)['currency'] ?? null;
    }

    private function getSettings(): Settings
    {
        return Settings::firstOrFail();
    }
}
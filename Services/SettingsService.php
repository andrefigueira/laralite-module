<?php

namespace Modules\Laralite\Services;

use Modules\Laralite\Models\Settings;

class SettingsService
{
    public function getCurrency()
    {
        return json_decode($this->getSettings()->settings , true)['currency'] ?? null;
    }

    public function getStripeKey(): string
    {
        return json_decode($this->getSettings()->settings, true)['stripeSecretKey'] ?? '';
    }

    private function getSettings(): Settings
    {
        return Settings::firstOrFail();
    }

    public function getSettingsArray()
    {
        return json_decode($this->getSettings()->settings, true);
    }
}
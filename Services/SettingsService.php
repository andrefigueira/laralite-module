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

    public function isFeeCollectionActive(): array
    {
        $settings = $this->getSettingsArray();
        if (
            $settings['feeActive'] === true &&
            !empty($settings['feeAmount']) &&
            !empty($settings['stripeSecretKey'])
        ) {
            return [
                'stripeSecretKey' => $settings['stripeSecretKey'],
                'feeAmount' => $settings['feeAmount'],
                'stripeAccessToken' => $settings['stripeAccessToken'],
                'connectedAccountId' => $settings['stripeAccountId'],
            ];
        }
        return [];
    }
}
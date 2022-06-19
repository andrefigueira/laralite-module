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

    public function getTaxAmount(): int
    {
        $settings = $this->getSettingsArray();
        if (!empty($settings['taxActive']) && !empty($settings['taxAmount'])) {
            return $settings['taxAmount'];
        }
        return 0;
    }

    public function getServiceFeeAmount(): int
    {
        $settings = $this->getSettingsArray();
        if (!empty($settings['serviceFeeActive']) && !empty($settings['serviceFeeAmount'])) {
            return $settings['serviceFeeAmount'];
        }
        return 0;
    }
}
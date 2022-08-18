<?php

namespace Modules\Laralite\Services;

use Modules\Laralite\Models\Settings;

class SettingsService
{
    public function getCurrency()
    {
        return $this->getSettings()->settings['currency'] ?? null;
    }

    public function getStripeKey(): string
    {
        return $this->getSettings()->settings['stripeSecretKey'] ?? 'UNSET';
    }

    private function getSettings(): Settings
    {
        return Settings::first();
    }

    public function getSettingsArray()
    {
        return $this->getSettings()->settings;
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
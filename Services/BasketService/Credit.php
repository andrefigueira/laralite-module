<?php

namespace Modules\Laralite\Services\BasketService;

use Modules\Laralite\Services\BasketServiceInterface;
use Modules\Laralite\Services\Models\BasketInterface;
use Modules\Laralite\Services\SettingsService;

class Credit implements BasketServiceInterface
{
    /**
     * @var SettingsService
     */
    private SettingsService $settingsService;

    /**
     * @var BasketInterface|null
     */
    private ?BasketInterface $basket;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function analyzeAndCorrectBasket(BasketInterface $basket)
    {
        // TODO: Implement analyzeAndCorrectBasket() method.
    }

    public function getModel(array $basket): BasketInterface
    {
        // TODO: Implement getModel() method.
    }
}
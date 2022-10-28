<?php

namespace Modules\Laralite\Services;

use Modules\Laralite\Services\Models\BasketInterface;

interface BasketServiceInterface
{
    public function analyzeAndCorrectBasket(BasketInterface $basket);
    public function getModel(array $basket): BasketInterface;
}
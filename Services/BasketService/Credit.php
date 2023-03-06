<?php

namespace Modules\Laralite\Services\BasketService;

use Modules\Laralite\Models\Product;
use Modules\Laralite\Services\BasketServiceInterface;
use Modules\Laralite\Services\Models\BasketInterface;
use Modules\Laralite\Services\Models\CreditBasket;
use Modules\Laralite\Services\SettingsService;

class Credit implements BasketServiceInterface
{
    /**
     * @var SettingsService
     */
    private SettingsService $settingsService;

    /**
     * @param BasketInterface|CreditBasket $basket
     * @return void
     */
    public function validateBasket(BasketInterface $basket): void
    {
        $products = $basket->getItems() ?? [];

        foreach ($products as $key => $product) {
            $sku = $product->getSku();
            /** @var Product $productModel */
            $productModel = Product::whereJsonContains(
                'variants',
                ['sku' => $sku]
            )->first();
            if (!$productModel) {
                continue;
            }
            $price = $productModel->getVariantPrice($sku, true);

            if ($price === 0) {
                $basket->getItems()->remove($key);
                continue;
            }
            $basket->getItems()->get($key)->setPrice($price);
            $basket->setTotal($price * $product->getQuantity());
        }
    }

    public function getModel(array $basket): BasketInterface
    {
        return new CreditBasket($basket);
    }
}
<?php

namespace Modules\Laralite\Services;

use Carbon\Carbon;
use Modules\Laralite\Models\Discount;
use Modules\Laralite\Models\Product;
use Modules\Laralite\Services\Models\Basket;

class BasketService
{
    /**
     * @var SettingsService
     */
    private SettingsService $settingsService;

    /**
     * @var Basket|null
     */
    private ?Basket $basket;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    /**
     * @param Basket $basket
     * @return float
     */
    public function getBasketTotal(Basket $basket): float
    {
        $this->analyzeAndCorrectBasket($basket);
        return $basket->getTotal();
    }

    public function analyzeAndCorrectBasket(Basket $basket)
    {
        $this->setModel($basket);
        $products = $this->basket->getItems() ?? [];
        $discounts = $this->basket->getDiscounts() ?? [];
        $total = 0;

        foreach ($products as $key => $product) {
            //TODO this query need to be updated to check if product is also active
            $sku = $product->getSku();
            /** @var Product $productModel */
            $productModel = Product::whereJsonContains(
                'variants',
                ['sku' => $sku]
            )->first();
            if (!$productModel) {
                continue;
            }
            $price = $productModel->getVariantPrice($sku);

            if ($price === 0) {
                $this->basket->getItems()->remove($key);
                continue;
            }
            $this->basket->getItems()->get($key)->setPrice($price);
            $this->basket->setTotal($price * $product->getQuantity());
        }

        $this->applyDiscounts($discounts)->applyTaxAmount();
        $this->basket->setServiceFee($this->settingsService->getServiceFeeAmount());
    }

    /**
     * @return $this
     */
    private function applyTaxAmount(): BasketService
    {
        if (!$tax = $this->settingsService->getTaxAmount()) {
            return $this;
        }

        $this->basket->setTaxAmount((float)($this->basket->getTotal() * $tax) / 100);
        return $this;
    }

    /**
     * @param Basket\Discounts $discounts
     * @return BasketService
     */
    private function applyDiscounts(Basket\Discounts $discounts): BasketService
    {
        $discountCodes = $discounts->arrayColumn('code');
        $discountModels = Discount::whereIn('code', $discountCodes)
            ->where(function ($query) {
                $query->whereDate('end_date', '>=', Carbon::now())
                    ->orWhereNull('end_date');
            })->get();

        $discountAmount = 0.00;

        /** @var Discount $discount */
        foreach ($discountModels as $discount) {
            $discountAmount += $discount->getDiscount($this->basket->getTotal());
        }
        $this->basket->setDiscountAmount(round($discountAmount, 2));

        return $this;
    }

    private function setModel(Basket $basket): BasketService
    {
        $this->basket = $basket;
        return $this;
    }

    /**
     * @param array $basket
     * @return Basket
     */
    public function getModel(array $basket): Basket
    {
        return new Basket($basket);
    }
}

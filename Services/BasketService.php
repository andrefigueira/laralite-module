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
     * @return int
     */
    public function getBasketTotal(Basket $basket): int
    {
        $this->analyzeAndCorrectBasket($basket);
        return $basket->getTotal();
    }

    public function analyzeAndCorrectBasket(Basket $basket): void
    {
        $this->setModel($basket);
        $products = $this->basket->getItems() ?? [];
        $discounts = $this->basket->getDiscounts()?: [];
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
            $creditsPrice = $productModel->getVariantPrice($sku, true);

            if ($price === 0) {
                $this->basket->getItems()->remove($key);
                continue;
            }
            $this->basket->getItems()->get($key)->setPrice($price);
            $this->basket->getItems()->get($key)->setCreditPrice($creditsPrice);
            $this->basket->setTotal($price * $product->getQuantity());
        }

        $this->applyDiscounts($discounts);
        $this->applyTaxAmount();
        $this->basket->setServiceFee($this->settingsService->getServiceFeeAmount());
    }

    public function generateBasketFromProducts(array $products)
    {

    }

    private function applyTaxAmount(): void
    {
        if (!$tax = $this->settingsService->getTaxAmount()) {
            return;
        }

        $this->basket->setTaxAmount((int)($this->basket->getTotal() * $tax) / 100);
    }

    /**
     * @param Basket\Discounts $discounts
     * @return void
     */
    private function applyDiscounts(Basket\Discounts $discounts): void
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

    }

    private function setModel(Basket $basket): void
    {
        $this->basket = $basket;
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

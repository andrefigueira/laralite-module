<?php

namespace Modules\Laralite\Services\BasketService;

use Carbon\Carbon;
use Modules\Laralite\Models\Discount;
use Modules\Laralite\Models\Product;
use Modules\Laralite\Services\BasketServiceInterface;
use Modules\Laralite\Services\Models\Basket;
use Modules\Laralite\Services\Models\BasketInterface;
use Modules\Laralite\Services\Models\Payment\PaymentAmount;
use Modules\Laralite\Services\SettingsService;
use Modules\Laralite\Services\StripeService;

class Standard implements BasketServiceInterface
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

    public function validateBasket(BasketInterface $basket): void
    {
        $products = $basket->getItems() ?? [];

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
                $basket->getItems()->remove($key);
                continue;
            }
            $basket->getItems()->get($key)->setPrice($price);
            $basket->setTotal($price * $product->getQuantity());
        }

        $this->applyDiscounts($basket);
        $this->applyTaxAmount($basket);

        if ($basket instanceof Basket\FeeAble) {
            $basket->setServiceFee($this->settingsService->getServiceFeeAmount());
        }
    }

    /**
     * @param BasketInterface $basket
     * @return void
     */
    private function applyTaxAmount(BasketInterface $basket): void
    {
        if (!$basket instanceof Basket\Taxable || !$tax = $this->settingsService->getTaxAmount()) {
            return;
        }

        $basket->setTaxAmount((int)($basket->getTotal() * $tax) / 100);
    }

    /**
     * @param BasketInterface $basket
     * @return void
     */
    private function applyDiscounts(BasketInterface $basket): void
    {
        if (!$basket instanceof Basket\Discountable) {
            return;
        }
        $discounts = $basket->getDiscounts();
        $discountCodes = $discounts->arrayColumn('code');
        $discountModels = Discount::whereIn('code', $discountCodes)
            ->where(function ($query) {
                $query->whereDate('end_date', '>=', Carbon::now())
                    ->orWhereNull('end_date');
            })->get();

        $discountAmount = 0.00;

        /** @var Discount $discount */
        foreach ($discountModels as $discount) {
            $discountAmount += $discount->getDiscount($basket->getTotal());
        }
        $basket->setDiscountAmount(round($discountAmount, 2));

    }

    /**
     * @param array $basket
     * @return BasketInterface
     */
    public function getModel(array $basket): BasketInterface
    {
        $basket = new Basket($basket);
        $this->validateBasket($basket);
        return $basket;
    }
}

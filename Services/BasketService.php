<?php

namespace Modules\Laralite\Services;

use Carbon\Carbon;
use Modules\Laralite\Models\Discount;
use Modules\Laralite\Models\Product;

class BasketService
{
    /**
     * @var SettingsService
     */
    private $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    /**
     * @param array $basket
     * @return float
     */
    public function getBasketTotal(array &$basket): float
    {
        $this->analyzeAndCorrectBasket($basket);
        return $basket['total'];
    }

    public function analyzeAndCorrectBasket(array &$basket)
    {
        $products = $basket['products'] ?? [];
        $discounts = $basket['discounts'] ?? [];
        $total = 0;

        foreach ($products as $key => $product) {
            //TODO this query need to be updated to check if product is also active
            $productModel = Product::find($product['id']);
            $price = $productModel->getVariantPrice($product['sku']);

            if ($price === 0) {
                unset($basket['products'][$key]);
                continue;
            }
            $basket['products'][$key]['price'] = $price;
            $total += $price * $product['quantity'];
        }

        $discountedTotal = $this->applyDiscounts($total, $discounts);
        $basket['discountAmount'] = round((float)($total - $discountedTotal), 2);
        $basket['taxAmount'] = $this->getTaxAmount($discountedTotal);
        $basket['serviceFee'] = $this->settingsService->getServiceFeeAmount();
        $discountedTotal += $this->getTaxAmount($discountedTotal);
        $discountedTotal += $this->settingsService->getServiceFeeAmount();
        $basket['total'] = $discountedTotal;
    }

    private function getTaxAmount(float $total): float
    {
        if (!$tax = $this->settingsService->getTaxAmount()) {
            return 0.00;
        }

       return (float)($total * $tax) / 100;
    }

    /**
     * @param float $total
     * @param array $discounts
     * @return float
     */
    private function applyDiscounts(float $total, array $discounts): float
    {
        $discountCodes = $discounts ? array_column($discounts, 'code') : [];
        $discounts = Discount::whereIn('code', $discountCodes)
            ->where(function ($query) {
                $query->whereDate('end_date', '>=', Carbon::now())
                    ->orWhereNull('end_date');
            })->get();


        /** @var Discount $discount */
        foreach ($discounts as $discount) {
            $total = $discount->applyDiscount($total);
        }

        return (float)$total;
    }
}

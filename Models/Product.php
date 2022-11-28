<?php

namespace Modules\Laralite\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * @package Modules\Laralite\Models
 * @mixin Eloquent
 */
class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'description',
        'meta',
        'images',
        'variants',
        'active',
        'credit_purchasable'
    ];

    protected $casts = [
        'meta' => 'object',
        'images' => 'array',
        'variants' => 'array',
    ];

    public function category()
    {
        return $this->hasOne(ProductCategory::class, 'id', 'category_id');
    }

    public function getVariantPrice(string $sku, bool $creditPrice = false): int
    {
        $variant = $this->getVariantBySku($sku);

        if (null === $variant) {
            return 0;
        }


        if ($creditPrice) {
            return $variant['pricing']['credits'] ?? 0;
        }

        $onSale = $variant['pricing']['on_sale'] ?? false;
        $standardPrice = $variant['pricing']['price'];
        $salePrice = $variant['pricing']['sale_price'];


        return $onSale ? (int)$salePrice : (int)$standardPrice;
    }

    public function getVariantBySku(string $sku): ?array
    {
        if (!is_array($this->variants)) {
            return null;
        }

        $foundVariant = null;
        foreach ($this->variants as $variant) {
            $currentSku = $variant['sku'] ?? '';
            if ($sku === $currentSku) {
                $foundVariant = $variant;
                break;
            }
        }

        return $foundVariant;
    }

    public function variantIsGroupable(string $sku)
    {
        $varient = $this->getVariantBySku($sku);
        return $varient['groupable'] ?? false;
    }

}

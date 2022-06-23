<?php


namespace Modules\Laralite\Services\Models\Basket;


interface ItemInterface
{
    public function getPrice();

    public function getId();

    public function getSku();

    public function getCreditPrice();

    public function getQuantity();
}
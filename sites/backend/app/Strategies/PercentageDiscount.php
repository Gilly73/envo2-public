<?php

namespace App\Strategies;

use App\Interfaces\DiscountStrategy;
use App\Repository\Interfaces\DiscountRepositoryInterface;

class PercentageDiscount implements DiscountStrategy
{
    private float $percentage;

    public function __construct(float $percentage)
    {
        $this->percentage = $percentage;
    }

    public function applyDiscount(float $price): float
    {
        return $price * (1 - $this->percentage);
    }
}
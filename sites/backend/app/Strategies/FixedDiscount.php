<?php

namespace App\Strategies;

use App\Interfaces\DiscountStrategy;

class FixedDiscount implements DiscountStrategy
{
    private float $amount;

    public function __construct(float $amount)
    {
        $this->amount = $amount;
    }

    public function applyDiscount(float $price): float
    {
        return max(0, $price - $this->amount);
    }
}
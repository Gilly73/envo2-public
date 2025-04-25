<?php

namespace App\Interfaces;

interface DiscountStrategy
{
    public function applyDiscount(float $price): float;
}
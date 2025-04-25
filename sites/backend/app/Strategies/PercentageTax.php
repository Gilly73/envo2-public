<?php

namespace App\Strategies;

use App\Interfaces\TaxStrategy;

class PercentageTax implements TaxStrategy
{
    private float $taxrate;

    public function __construct(float $taxrate)
    {
        $this->taxrate = $taxrate;
    }

    public function calculateTax(float $price): float
    {
        return $price * $this->taxrate;
    }
}
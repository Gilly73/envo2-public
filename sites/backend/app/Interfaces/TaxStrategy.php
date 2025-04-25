<?php

namespace App\Interfaces;

interface TaxStrategy
{
    public function calculateTax(float $price): float;
}
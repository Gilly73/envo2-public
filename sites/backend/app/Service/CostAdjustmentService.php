<?php

namespace App\Service;

use App\Interfaces\DiscountStrategy;
use App\Interfaces\TaxStrategy;
use App\Strategies\PercentageTax;
use App\Strategies\PercentageDiscount;
use App\Models\Discount;
use App\Models\Tax;
use App\Models\Country;

class CostAdjustmentService
{
    private ?DiscountStrategy $discountStrategy;
    private ?TaxStrategy $taxStrategy;

    public function __construct(?DiscountStrategy $discountStrategy = null, ?TaxStrategy $taxStrategy = null)
    {
        $this->discountStrategy = $discountStrategy;
        $this->taxStrategy = $taxStrategy;
    }

    public function calculateCost(float $baseCost): array
    {
        // Apply discount if available
        $discountAmount = $this->discountStrategy ? $baseCost - $this->discountStrategy->applyDiscount($baseCost) : 0.00;
        $costAfterDiscount = $baseCost - $discountAmount;

        // Apply tax if available
        $taxAmount = $this->taxStrategy ? $this->taxStrategy->calculateTax($costAfterDiscount) : 0.00;

        // Compute total cost
        $totalCost = $costAfterDiscount + $taxAmount;

        return [
            'cost' => $this->formatprices($baseCost),
            'discount'  => $this->formatprices($discountAmount),
            'tax'       => $this->formatprices($taxAmount),
            'totalCost' => $this->formatprices($totalCost)
        ];
    }

    public static function createWithStrategies(?string $discountCode, ?string $country): self
    {
        $instance = new self();
        
        // Get the discount strategy
        $discountStrategy = $instance->getDiscountStrategy($discountCode,$country);
        
        // Get the tax strategy
        $taxStrategy = $instance->getTaxStrategy($country);
    
        return new self($discountStrategy, $taxStrategy);
    }
    

    private function formatprices(float $cost): string
    {
        return number_format($cost, 2, '.', '');

    }

    public function applyDiscount(float $couchCost, string $discountCode, string $countryCode): float
    {
        $discountStrategy = $this->getDiscountStrategy($discountCode,$countryCode);
        if ($discountStrategy) {
            return $discountStrategy->applyDiscount($couchCost);
        }

        return $couchCost;
    }

    public function calculateTax(float $finalCost, string $countryCode): float
    {
        $taxStrategy = $this->getTaxStrategy($countryCode);
        if ($taxStrategy) {
            return $taxStrategy->calculateTax($finalCost);
        }

        return 0.00;
    }

    private function getDiscountStrategy(?string $code, string $countryCode): ?DiscountStrategy
    {
        if ($code === null) {
            return null;
        }
        
        return new PercentageDiscount($this->getDiscount($code,$countryCode));
    }

    private function getDiscount(string $discountCode, string $country): float
    {   
        $countryCode = $this->findCountryCode($country);
        return ($countryCode) ? $this->findDiscount($discountCode, $countryCode) : 0.00;
    }

    private function findDiscount(string $discountCode, Int $countryCode): float
    {
        $discount = Discount::where('code', $discountCode)->where('country_id', $countryCode)->where('active', 1)->first();
        return ($discount === null) ? 0.00 : $discount->discount;
    }

    private function findCountryCode(string $country): int
    {
        $countryCode = Country::where([['country','=', $country],['active','=', 1]])->first();
        return ($countryCode === null) ? 0 : $countryCode['id'];
    }

    private function getTaxStrategy(string $country): ?TaxStrategy
    {
        $countryCode = $this->findCountryCode($country);
        if (!$countryCode) {
            return null;
        } 
        $taxRate = $this->findTax($countryCode);
        return ($taxRate) ? new PercentageTax($taxRate) : 0.00;
    }

    private function findTax(Int $countryCode): float
    {
        $tax = Tax::where('country_id', $countryCode)->where('active', 1)->first();
        return ($tax === null) ? 0.00 : $tax->tax;
    }

}
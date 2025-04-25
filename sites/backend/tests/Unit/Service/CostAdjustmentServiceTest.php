<?php

namespace Tests\Unit\Service;

use Tests\TestCase;
use Mockery;
use App\Service\CostAdjustmentService;
use App\Interfaces\DiscountStrategy;
use App\Interfaces\TaxStrategy;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class CostAdjustmentServiceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    // protected function tearDown(): void
    // {
    //     Mockery::close();
    //     parent::tearDown();
    // }
    
    public function test_calculate_cost_without_strategies()
    {
        // Arrange: No discount or tax strategies provided.
        $service = new CostAdjustmentService(null, null);
        $baseCost = 100.0;
        
        // Act
        $result = $service->calculateCost($baseCost);
        
        // Assert: The cost remains unchanged, discount and tax are zero.
        $this->assertEquals([
            'cost'      => '100.00',
            'discount'  => '0.00',
            'tax'       => '0.00',
            'totalCost' => '100.00'
        ], $result);
    }
    
    public function test_calculate_cost_with_discount_only()
    {
        // Arrange:
        // Create a mock discount strategy.
        $discountStrategy = Mockery::mock(DiscountStrategy::class);
        // For a base cost of 100, suppose the discount strategy returns a discounted cost of 80.
        // That means the discount amount is 100 - 80 = 20.
        $discountStrategy->shouldReceive('applyDiscount')
                         ->with(100.0)
                         ->once()
                         ->andReturn(80.0);
                         
        $service = new CostAdjustmentService($discountStrategy, null);
        $baseCost = 100.0;
        
        // Act: discountAmount = 100 - 80 = 20, costAfterDiscount = 80.
        $result = $service->calculateCost($baseCost);
        
        // Assert:
        $this->assertEquals([
            'cost'      => '100.00',
            'discount'  => '20.00',
            'tax'       => '0.00',
            'totalCost' => '80.00'
        ], $result);
    }
    
    public function test_calculate_cost_with_tax_only()
    {
        // Arrange:
        // Create a mock tax strategy.
        $taxStrategy = Mockery::mock(TaxStrategy::class);
        // For a base cost of 100, with no discount the cost after discount remains 100.
        // Suppose the tax strategy calculates a tax of 15.
        $taxStrategy->shouldReceive('calculateTax')
                    ->with(100.0)
                    ->once()
                    ->andReturn(15.0);
                    
        $service = new CostAdjustmentService(null, $taxStrategy);
        $baseCost = 100.0;
        
        // Act: discount = 0, tax = 15, so totalCost = 100 + 15 = 115.
        $result = $service->calculateCost($baseCost);
        
        // Assert:
        $this->assertEquals([
            'cost'      => '100.00',
            'discount'  => '0.00',
            'tax'       => '15.00',
            'totalCost' => '115.00'
        ], $result);
    }
    
    public function test_calculate_cost_with_both_discount_and_tax()
    {
        // Arrange:
        $discountStrategy = Mockery::mock(DiscountStrategy::class);
        $taxStrategy = Mockery::mock(TaxStrategy::class);
        
        // For base cost 100, assume discount strategy returns 90,
        // so discountAmount = 100 - 90 = 10, and costAfterDiscount = 90.
        $discountStrategy->shouldReceive('applyDiscount')
                         ->with(100.0)
                         ->once()
                         ->andReturn(90.0);
                         
        // For costAfterDiscount of 90, assume tax strategy returns 9.
        $taxStrategy->shouldReceive('calculateTax')
                    ->with(90.0)
                    ->once()
                    ->andReturn(9.0);
                    
        $service = new CostAdjustmentService($discountStrategy, $taxStrategy);
        $baseCost = 100.0;
        
        // Act: discount = 10, costAfterDiscount = 90, tax = 9, totalCost = 90 + 9 = 99.
        $result = $service->calculateCost($baseCost);
        
        // Assert:
        $this->assertEquals([
            'cost'      => '100.00',
            'discount'  => '10.00',
            'tax'       => '9.00',
            'totalCost' => '99.00'
        ], $result);
    }
}

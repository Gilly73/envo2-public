<?php

namespace Tests\Unit;

use App\Factory\CouchBuilder;
use App\Interfaces\CouchComponent;
use Tests\TestCase;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class CouchBuilderTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    // protected function tearDown(): void
    // {
    //     Mockery::close();
    //     parent::tearDown();
    // }
   
    public function test_get_description_with_base_couch_component()
    {
        // Arrange:
        // Overload the Couch class so that when CouchBuilder creates a new Couch,
        // it returns our mocked instance.
        $mockCouch = Mockery::mock('overload:App\Factory\Couch');
        $mockCouch->shouldReceive('getDescription')
                  ->once()
                  ->andReturn('Couch = Indoor');
        
        // Instantiate CouchBuilder with a couch type ID.
        // The constructor internally creates a new Couch($couchTypeId),
        // which will be intercepted and replaced by our $mockCouch.
        $builder = new CouchBuilder(1);
        
        // Create mocks for each component that implements CouchComponent.
        $styleComponent = Mockery::mock(CouchComponent::class);
        $styleComponent->shouldReceive('getDescription')
                       ->once()
                       ->andReturn('Style = Modern Indoor Comfort');
        
        $fabricComponent = Mockery::mock(CouchComponent::class);
        $fabricComponent->shouldReceive('getDescription')
                        ->once()
                        ->andReturn('Fabric = Cotton');
        
        $legComponent = Mockery::mock(CouchComponent::class);
        $legComponent->shouldReceive('getDescription')
                     ->once()
                     ->andReturn('Leg = Brown wooden leg');
        
        $seaterComponent = Mockery::mock(CouchComponent::class);
        $seaterComponent->shouldReceive('getDescription')
                        ->once()
                        ->andReturn('Seater = 4 seater - indoor');
        
        // Act:
        // Add components to the builder.
        $builder->addComponent($styleComponent);
        $builder->addComponent($fabricComponent);
        $builder->addComponent($legComponent);
        $builder->addComponent($seaterComponent);
        
        // The final description is the base couch description followed by each component's description.
        $actual = $builder->getDescription();
        
        // Build expected description.
        $expected = 'Couch = Indoor, Style = Modern Indoor Comfort, Fabric = Cotton, Leg = Brown wooden leg, Seater = 4 seater - indoor';
        
        // Assert:
        $this->assertEquals($expected, $actual);
    }

    public function test_get_cost_returns_correct_sum()
    {
        // Arrange:
        // Overload the Couch class so that when CouchBuilder instantiates a new Couch,
        // it returns our mocked instance.
        $baseCost = 100.0;
        $mockCouch = Mockery::mock('overload:App\Factory\Couch');
        $mockCouch->shouldReceive('getCost')
                  ->once()
                  ->andReturn($baseCost);
        
        // Instantiate the builder (this will create a new Couch instance,
        // but our overload returns our mock instead).
        $builder = new CouchBuilder(1);
        
        // Create mocks for additional components
        $component1 = Mockery::mock(CouchComponent::class);
        $component1->shouldReceive('getCost')
                   ->once()
                   ->andReturn(50.0);
        
        $component2 = Mockery::mock(CouchComponent::class);
        $component2->shouldReceive('getCost')
                   ->once()
                   ->andReturn(25.0);
        
        // Add components to the builder
        $builder->addComponent($component1);
        $builder->addComponent($component2);
        
        // Act: Calculate total cost.
        // Expected cost = base couch cost + sum of components' cost
        //               = 100 + 50 + 25 = 175.0
        $expectedCost = 175.0;
        $actualCost = $builder->getCost();
        
        // Assert:
        $this->assertEquals($expectedCost, $actualCost);
    }
    
    public function test_get_components_returns_added_components()
    {
        // Arrange:
        // Overload the Couch class to satisfy the builder instantiation.
        $mockCouch = Mockery::mock('overload:App\Factory\Couch');
        // We don't care about the cost or description in this test.
        $mockCouch->shouldReceive('getCost')->andReturn(0.0);
        $mockCouch->shouldReceive('getDescription')->andReturn('Couch = Indoor');
        
        $builder = new CouchBuilder(1);
        
        // Create dummy components.
        $component1 = Mockery::mock(CouchComponent::class);
        $component2 = Mockery::mock(CouchComponent::class);
        
        // Act:
        $builder->addComponent($component1);
        $builder->addComponent($component2);
        $components = $builder->getComponents();
        
        // Assert:
        $this->assertCount(2, $components);
        $this->assertSame($component1, $components[0]);
        $this->assertSame($component2, $components[1]);
    }
}

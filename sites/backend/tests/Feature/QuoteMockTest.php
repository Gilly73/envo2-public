<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Service\CouchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
//https://chillbits.com/2020/12/07/trying-to-get-property-_mockery_expectations_count-of-non-object/
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class QuoteMockTest extends TestCase
{
    use RefreshDatabase, MockeryPHPUnitIntegration;
    protected $quoteApiUrl;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->withoutMiddleware();
        $this->quoteApiUrl = '/api/v1/quote/couch';
    }

    // protected function tearDown(): void
    // {
    //     Mockery::close();
    //     parent::tearDown();
    // }
    
    public function test_get_quote_returns_expected_json()
    {

        $fakeService = Mockery::mock(CouchService::class);
        $fakeService->shouldReceive('getCost')
            ->once()
            ->andReturn([
                'cost'      => '430.00',
                'discount'  => '0.00',
                'tax'       => '86.00',
                'totalCost' => '516.00'
            ]);
        $fakeService->shouldReceive('getDescription')
            ->once()
            ->andReturn('Indoor Couch, Style = Modern Indoor Comfort, Fabric = Velvet, Leg = Black wooden leg, Seater = 2 seater - indoor');
        
        // Bind the fake service into the container so the controller receives it.
        $this->app->instance(CouchService::class, $fakeService);
        
        // Prepare valid request data.
        $data = [
            'couchtype'  => 1,
            'styletype'  => 1,
            'fabrictype' => 1,
            'legtype'    => 1,
            'seatertype' => 1,
            'country'    => 'UK'
        ];
        
        // Act: 
        $response = $this->json('POST', $this->quoteApiUrl, $data);
        
        // Assert:
        $response->assertStatus(200)
                 ->assertJson([
                     'description' => 'Indoor Couch, Style = Modern Indoor Comfort, Fabric = Velvet, Leg = Black wooden leg, Seater = 2 seater - indoor',
                     'couchCost'   => '430.00',
                     'discount'    => '0.00',
                     'tax'         => '86.00',
                     'totalCost'   => '516.00',
                 ]);
    }
}

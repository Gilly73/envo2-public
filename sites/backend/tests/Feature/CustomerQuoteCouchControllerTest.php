<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CustomerQuoteCouchControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $quoteApiUrl;
    protected $fakeCouchService;

    protected function setUp(): void
    {
        parent::setUp();
        //$this->refreshApplication();
        //$this->app->forgetInstance(\App\Service\CouchService::class);
        // Disable Sentry for tests
        config(['sentry.dsn' => null]);
        config(['sentry.traces_sample_rate' => 0]);


        $this->seed();
        $this->withoutMiddleware();
        $this->quoteApiUrl = '/api/v1/quote/couch';
    }


    // public function test_it_sets_up_correctly()
    // {
    //     $this->assertTrue(Schema::hasTable('countries'));
    //     $this->assertTrue(Schema::hasTable('taxes'));
    //     $this->assertTrue(Schema::hasTable('discounts'));
    // }

    /**
     * Test that an invalid request (e.g., missing required field) returns a validation error.
     *
     * @return void
     */
    public function test_get_quote_validation_error()
    {
        // Arrange: Missing the required 'couchtype' field
        $data = [
            'fabrictype' => 'leather',
            'legtype'    => 'metal',
            'seatertype' => '2',
            'discount'   => 'SAVE20',
            'country'    => 'US'
        ];

        // Act: Make a POST request with the invalid data
        $response = $this->json('POST', $this->quoteApiUrl, $data);

        $response->assertStatus(422)
            ->assertJsonStructure(['errors']);
    }

    /**
     * Test that a valid request returns a successful response.
     *
     * @return void
     */
    public function test_get_quote_successful_response()
    {

        // $couchtype = DB::table('couch_types')->count();
        // info("couchtype count: " . $couchtype);
        // $data = DB::table('couch_types')->get();
        // info($data);

        // $seatersCount = DB::table('seaters')->count();
        // info("seater count bf: " . $seatersCount);
        // $data = DB::table('seaters')->get();
        // info($data);

        // $fabricsCount = DB::table('fabrics')->count();
        // info("fabric count bf: " . $fabricsCount);
        // $data = DB::table('fabrics')->get();
        // info($data);

        // $legsCount = DB::table('legs')->count();
        // info("legs count bf: " . $legsCount);

        // $stylesCount = DB::table('styles')->count();
        // info("Styles count bf: " . $stylesCount);
        // $data = DB::table('styles')->get();
        // info($data);

        $data = [
            'couchtype'  => "1",
            'styletype' => "1",
            'fabrictype' => "1",
            'legtype'    => "1",
            'seatertype' => "3",
            'discount'   => 'UKSAVE10',
            'country'    => 'UK'
        ];

        // Act: Make a POST request to the endpoint
        $response = $this->json('POST', $this->quoteApiUrl, $data);
        info($response->getContent());


        $response->assertStatus(200)
            ->assertJsonStructure([
                'description',
                'couchCost',
                'discount',
                'tax',
                'totalCost'
            ])->assertJson([
                'description' => 'Couch = Indoor, Style = Modern Indoor Comfort, Fabric = Velvet, Leg = Black wooden leg, Seater = 4 seater - indoor',
                'couchCost' => '560.00',
                'discount' => '56.00',
                'tax' => '100.80',
                'totalCost' => '604.80'
            ]);
    }

    public function test_get_couch_exception_response()
    {
        $data = [
            'couchtype'  => 1,
            'styletype' => 1,
            'fabrictype' => 1,
            'legtype'    => 1,
            'seatertype' => 4,
            'discount'   => 'UKSAVE10',
            'country'    => 'UK'
        ];

        // Act: Make a POST request to the endpoint
        $response = $this->json('POST', $this->quoteApiUrl, $data);
        info($response->getContent());


        $response->assertBadRequest()
            ->assertJson([
                "errors" => "Seater not found : 4"
            ]);
    }
}

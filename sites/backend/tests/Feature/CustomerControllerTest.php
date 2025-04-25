<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Customer;
use App\Repository\Interfaces\CustomerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
    }

    /** @test */
    public function it_get_all_customers_successful_response()
    {
        // Seed the database with the data
        Customer::factory()->count(50)->create();

        $this->get("/api/v1/customers")
            ->assertStatus(200)
            ->assertJsonStructure([ // Optionally, check the structure of a single customer
                'data' => [
                    '*' => [ // Ensure each customer has these fields
                        'id',
                        'first_name',
                        'last_name',
                        'date_of_birth',
                        'email',
                        'mobile',
                        'address_1',
                        'address_2',
                        'city',
                        'county',
                        'postcode',
                        'country'
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_create_customer_successful_response()
    {

        $data = $this->customerData();

        $this->post("api/v1/customer/create", $data)
            ->assertStatus(201)
            ->assertJsonStructure(array_keys($data), $data);
    }

    /** @test */
    public function it_update_customer_successful_response()
    {
        $data = $this->customerData();

        $this->post("api/v1/customer/create", $data)
            ->assertStatus(201);

        $updatedData = $data;
        $mobile = $this->faker->phoneNumber();
        $updatedData['address1'] = 'Updated Street Name';
        $updatedData['mobile'] = $mobile;

        $normalizedMobile = preg_replace('/\D/', '', $mobile);
        $this->post("api/v1/customer/create", $updatedData)
            ->assertStatus(201)
            ->assertJsonFragment([
                'mobile' => $normalizedMobile,
                'address_1' => 'Updated Street Name',
                'email'     => $data['email'], // The email should remain unchanged.
            ]);
    }

    /** @test */
    public function it_duplicate_email_feature()
    {
        // Define the input data.
        $data = $this->customerData();
        $data['email'] = 'duplicate@gmail.com';
        // Create two Customer instances with the same email.
        $customer1 = new Customer($data);
        $customer1->id = 1;
        $customer2 = new Customer($data);
        $customer2->id = 2;

        // Create a collection with both customers.
        $duplicateCustomers = new Collection([$customer1, $customer2]);

        // Create a mock for the CustomerRepositoryInterface.
        $repositoryMock = \Mockery::mock(CustomerRepositoryInterface::class);
        // When findByEmail is called with the duplicate email, return the duplicate collection.
        $repositoryMock->shouldReceive('findByEmail')
            ->with('duplicate@gmail.com')
            ->andReturn($duplicateCustomers);

        // Optionally, ensure that the create() and update() methods are not called.
        $repositoryMock->shouldNotReceive('create');
        $repositoryMock->shouldNotReceive('update');

        // Bind the repository mock in the container so that the service gets it.
        $this->app->instance(CustomerRepositoryInterface::class, $repositoryMock);

        // Act: Make the POST request.
        $response = $this->post('api/v1/customer/create', $data);

        // Assert: the response should have a 409 status and proper error message.
        $response->assertStatus(409)
            ->assertJson([
                'message' => 'Duplicate Customer Found: duplicate@gmail.com'
            ]);
    }


    private function customerData()
    {
        return [
            'firstName' => $this->faker->firstName(),
            'surname'   => $this->faker->lastName(),
            'dob'       => '2020-08-03',
            'email'     => $this->faker->unique()->freeEmail(),
            'mobile'    => $this->faker->phoneNumber(),
            'address1'  => $this->faker->streetName(),
            'address2'  => null,
            'city'      => $this->faker->city(),
            'county'    => 'Bucks',
            'postcode'  => $this->faker->postcode(),
            'country'   => $this->faker->randomElement(['UK', 'US'])
        ];
    }
}

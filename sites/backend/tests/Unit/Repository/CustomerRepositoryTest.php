<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Customer;
use App\Repository\Interfaces\CustomerRepositoryInterface;
use App\Repository\EloquentCustomerRepository;

class CustomerRepositoryTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->customerRepository = new EloquentCustomerRepository(new Customer());
    }

     /** @test */
     public function it_binds_the_customer_repository_interface_to_the_eloquent_implementation()
     {
         $repository = app(CustomerRepositoryInterface::class);
         $this->assertInstanceOf(EloquentCustomerRepository::class, $repository);
     }

    /** @test */
    public function it_can_create_a_customer()
    {
    
        $data = Customer::factory()->make([
            'email' => 'john@gmail.com',
        ]);

        $customer = $this->customerRepository->create($data->toArray());

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertDatabaseHas('customers', [
            'email' => 'john@gmail.com',
        ]);
    }

    /** @test */
    public function it_can_find_a_customer()
    {
        // Create a customer using a factory
        $createdCustomer = Customer::factory()->create();

        // Find the customer using the repository
        $foundCustomer = $this->customerRepository->find($createdCustomer->id);

        $this->assertNotNull($foundCustomer);
        $this->assertEquals($createdCustomer->id, $foundCustomer->id);
    }

    /** @test */
    public function it_can_update_a_customer()
    {
        $customer = Customer::factory()->create([
            'email' => 'john@gmail.com'
        ]);

        $updated = $this->customerRepository->update($customer->id, [
            'email' => 'john@hotmail.com'
        ]);

        $this->assertInstanceOf(Customer::class, $updated);
        $this->assertEquals('john@hotmail.com', $updated->email);
        
        $this->assertDatabaseHas('customers', [
            'id'   => $customer->id,
            'email' => 'john@hotmail.com'
        ]);
    }

    /** @test */
    public function it_can_delete_a_customer()
    {
        $customer = Customer::factory()->create();

        $deleted = $this->customerRepository->delete($customer->id);

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('customers', [
            'id' => $customer->id
        ]);
    }
}

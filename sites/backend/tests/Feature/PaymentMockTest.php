<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Service\Payment\PaymentGatewayInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class PaymentMockTest extends TestCase
{
    use RefreshDatabase, MockeryPHPUnitIntegration;
    protected $customer;
    protected $paymentIntentApiUrl;
    protected $paymentRefundApiUrl;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
        $this->customer = Customer::factory()->create();
        $this->paymentIntentApiUrl = '/api/v1/payment/create-intent';
        $this->paymentRefundApiUrl = '/api/v1/payment/refund';
    }


    /**
     * Test that createPaymentIntent returns the client secret on success.
     */
    public function test_create_payment_intent_successful()
    {

        $this->createPaymentIntentSuccessfulMockery();

        $response = $this->actingAs($this->customer, 'sanctum')
            ->postJson($this->paymentIntentApiUrl, [
                'quote' => ['totalCost' => 10],
                'customer' => $this->customer->toArray()
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'clientSecret' => 'secret_123',
            ]);
    }

    /**
     * Test that refundPayment returns the refund data on success.
     */
    public function test_refund_payment_successful()
    {
        // Dummy refund data.
        $dummyRefundData = [
            'status'    => 'succeeded',
            'refund_id' => 'refund_123',
        ];

        $chargeId = 'charge_abc';

        // Create a mock for PaymentGatewayInterface.
        $mockGateway = Mockery::mock(PaymentGatewayInterface::class);

        // Expect refundPayment to be called with the charge ID.
        $mockGateway->shouldReceive('refundPayment')
            ->once()
            ->with($chargeId)
            ->andReturn($dummyRefundData);

        $this->app->instance(PaymentGatewayInterface::class, $mockGateway);

        // Act as the authenticated user and send the POST request.
        $response = $this->actingAs($this->customer, 'sanctum')
            ->postJson($this->paymentRefundApiUrl, [
                'charge_id' => $chargeId,
            ]);

        $response->assertStatus(200)
            ->assertJson($dummyRefundData);
    }

    /**
     * Optionally, test the error scenario when createPaymentIntent fails.
     */
    public function test_create_payment_intent_error()
    {

        $errorMessage = 'Payment error occurred';
        $customerId = 'cust_123';

        $mockGateway = Mockery::mock(PaymentGatewayInterface::class);

        $mockGateway->shouldReceive('createCustomer')
            ->once()
            ->with(Mockery::subset($this->customer->toArray()))
            ->andReturn($customerId);

        $mockGateway->shouldReceive('createPaymentIntent')
            ->once()
            ->with($customerId, 1000, 'gbp', true)
            ->andThrow(new \Exception($errorMessage));

        $this->app->instance(PaymentGatewayInterface::class, $mockGateway);

        $response = $this->actingAs($this->customer, 'sanctum')
            ->postJson($this->paymentIntentApiUrl, [
                'quote' => ['totalCost' => 10],
                'customer' => $this->customer->toArray()
            ]);

        $response->assertStatus(500)
            ->assertJson([
                'error' => $errorMessage,
            ]);
    }

    protected function createPaymentIntentSuccessfulMockery(): void
    {
        // Create a dummy PaymentIntent object.
        $dummyPaymentIntent = new \stdClass();
        $dummyPaymentIntent->client_secret = 'secret_123';

        // Prepare a dummy customer ID.
        $customerId = 'cust_123';

        // Create a mock for PaymentGatewayInterface.
        $mockGateway = Mockery::mock(PaymentGatewayInterface::class);

        //Expect the createCustomer method to be called with the user.
        $mockGateway->shouldReceive('createCustomer')
            ->once()
            ->with(Mockery::subset($this->customer->toArray()))
            ->andReturn($customerId);

        // Expect the createPaymentIntent method to be called with the given parameters.
        $mockGateway->shouldReceive('createPaymentIntent')
            ->once()
            ->with($customerId, 1000, 'gbp', true)
            ->andReturn($dummyPaymentIntent);

        // Bind the mock into the Laravel container.
        $this->app->instance(PaymentGatewayInterface::class, $mockGateway);
        return;
    }
}

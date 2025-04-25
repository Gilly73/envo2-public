<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;

class PaymentIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected $customer;
    protected $stripe;
    protected $paymentIntentApiUrl;
    protected $paymentRefundApiUrl;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a customer instance.
        $this->customer = Customer::factory()->create();

        // Ensure that the real PaymentGateway implementation is used.
        // (Don't bind any mock for PaymentGatewayInterface.)
        // Initialize Stripe client with the secret key from configuration.
        $this->stripe = new StripeClient(config('services.stripe.secret'));
        $this->paymentIntentApiUrl = '/api/v1/payment/create-intent';
        $this->paymentRefundApiUrl = '/api/v1/payment/refund';
    }

    /**
     * Test that the create payment intent endpoint returns a real Stripe client secret.
     */
    public function test_create_payment_intent_and_confirm()
    {
        $chargeId = $this->createPaymentIntentAndConfirm();
        $this->assertNotEmpty($chargeId, 'Charge Id is empty');
    }

    /**
     * Test that the refund payment endpoint successfully refunds a payment.
     */
    public function test_refund_payment_with_stripe()
    {
        $chargeId = $this->createPaymentIntentAndConfirm();
        $this->assertNotEmpty($chargeId, 'Charge Id is empty');

        // Step 4: Refund the payment using your API.
        $refundResponse = $this->actingAs($this->customer, 'sanctum')
            ->postJson($this->paymentRefundApiUrl, [
                'charge_id' => $chargeId,
            ]);

        // Assert the refund was successful.
        $refundResponse->assertStatus(200);
        $refund = $refundResponse->json();
      
        $this->assertEquals('succeeded', $refund['status']);
        $this->assertStringStartsWith('re_', $refund['id']);
    }

    protected function createPaymentIntentAndConfirm() 
    {
        $response = $this->createPaymentIntent();
        $response->assertStatus(200);
        $paymentIntentId = $this->retrievePaymentIntentId($response);

        // Confirm the PaymentIntent so that a charge is generated.
        $response = $this->confirmPaymentIntent($paymentIntentId);

        // Retrieve the PaymentIntent to extract the charge ID.
        $chargeId = $this->retrieveChargeId($paymentIntentId);
        return $chargeId;
    }

    /**
     * Helper method to create a PaymentIntent.
     */
    protected function createPaymentIntent() 
    {
        $response = $this->actingAs($this->customer, 'sanctum')
            ->postJson($this->paymentIntentApiUrl, [
                'customer' => $this->customer->toArray(),
                'quote' => ['totalCost' => 10],
                'confirm' => false,
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                'return_url' => 'https://example.com/payment-complete',
            ]);
        
        return $response;
    }

     /**
     * Helper method to confirm a PaymentIntent.
     *
     * @param string $paymentIntentId
     */
    protected function confirmPaymentIntent(string $paymentIntentId): void
    {
        $this->stripe->paymentIntents->confirm($paymentIntentId, [
            'payment_method' => 'pm_card_visa',
            'return_url'     => 'https://example.com/payment-complete',
        ]);
    }

    protected function retrievePaymentIntentId($response) : string
    {
        $data = $response->json();
        $this->assertArrayHasKey('clientSecret', $data);
        $this->assertStringStartsWith('pi_', $data['clientSecret']);

        $clientSecret = $data['clientSecret'] ?? '';   
        //pi_3Qw5idDCxyTgbCe40eEE9euI_secret_QYxt8O9Cdix1SsSXhtLkFzABf
        $parts = explode('_secret', $clientSecret);
        return $parts[0];
    }

    /**
     * Helper method to retrieve the charge ID from a PaymentIntent.
     *
     * @param string $paymentIntentId
     * @return string Charge ID.
     */
    protected function retrieveChargeId(string $paymentIntentId): string
    {
        $paymentIntent = $this->stripe->paymentIntents->retrieve($paymentIntentId, [
            'expand' => ['charges'],
        ]);
        return (isset($paymentIntent->latest_charge) && $paymentIntent->latest_charge) ? $paymentIntent->latest_charge : '';
    }
    
}

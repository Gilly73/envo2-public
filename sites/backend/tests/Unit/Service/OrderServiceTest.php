<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Service\PaymentService;
use App\Repository\Interfaces\PaymentRepositoryInterface;
use App\DTO\PaymentIntentDTO;
use App\Models\Payment;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;


class OrderServiceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testCreatePaymentUsingDTO()
    {
        // Simulate data that would be part of the OrderRequest payload
        $orderRequestData = [
            'customer_id' => 123,
            'payment' => [
                'paymentIntent' => [
                    'id' => 'pi_3R95UPDCxyTgbCe41FuWCDot',
                    'amount' => 55200,
                    'currency' => 'gbp',
                    'payment_method' => 'pm_1R95UQDCxyTgbCe418H5Taho',
                    'status' => 'succeeded',
                ]
            ]
        ];

        // Build the payment data array exactly as done in your process() method.
        $paymentData = [
            'customer_id'       => $orderRequestData['customer_id'],
            'payment_intent_id' => $orderRequestData['payment']['paymentIntent']['id'],
            'amount'            => $orderRequestData['payment']['paymentIntent']['amount'],
            'currency'          => $orderRequestData['payment']['paymentIntent']['currency'],
            'payment_method'    => $orderRequestData['payment']['paymentIntent']['payment_method'],
            'status'            => $orderRequestData['payment']['paymentIntent']['status'],
        ];

        // Convert the array to a DTO then back to an array (simulate what your process() does)
        $paymentDto = PaymentIntentDTO::fromArray($paymentData)->toArray();

        // Define what the repository should return upon creating the payment.
        $expectedPayment = new Payment([
            'order_id'          => null,
            'customer_id'       => 123,
            'payment_intent_id' => 'pi_3R95UPDCxyTgbCe41FuWCDot',
            'amount'            => 55200,
            'currency'          => 'gbp',
            'payment_method'    => 'pm_1R95UQDCxyTgbCe418H5Taho',
            'status'            => 'succeeded',
        ]);

        // Create a mock of the PaymentRepositoryInterface.
        /** @var \App\Repository\Interfaces\PaymentRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject $paymentRepositoryMock */
        $paymentRepositoryMock = $this->createMock(PaymentRepositoryInterface::class);

        // Expect the repository's create method to be called once with $paymentDto,
        // and to return the expected payment object.
        $paymentRepositoryMock->expects($this->once())
            ->method('create')
            ->with($paymentDto)
            ->willReturn($expectedPayment);

        // Instantiate the PaymentService with the mocked repository.
        $paymentService = new PaymentService($paymentRepositoryMock);

        // Call the create method using the DTO data.
        $result = $paymentService->create($paymentDto);

        // Assert that the returned value matches what we expected.
        $this->assertEquals($expectedPayment, $result);
    }
}

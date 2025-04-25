<?php
namespace App\Service\Payment;

interface PaymentGatewayInterface
{
    /**
     * Create (or retrieve) a customer in the payment gateway.
     *
     * @param mixed $customer
     * @return string  Customer identifier in the gateway.
     */
    public function createCustomer($customer);

    /**
     * Create a payment intent (or equivalent) for a customer.
     *
     * @param string $customerId
     * @param int    $amount
     * @param string $currency
     * @param bool   $setupFutureUsage
     * @return mixed Payment intent object.
     */
    public function createPaymentIntent($customerId, $amount, $currency = 'gbp', $setupFutureUsage = false);

    /**
     * Refund a payment.
     *
     * @param string $chargeId
     * @return mixed Refund object.
     */
    public function refundPayment($chargeId);
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\Payment\PaymentGatewayInterface;
class PaymentController extends Controller
{
    protected $paymentGateway;

    public function __construct(PaymentGatewayInterface $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function createPaymentIntent(Request $request)
    {

        // Create or retrieve the customer ID via the payment gateway.
        $customerId = $this->paymentGateway->createCustomer($request['customer']);
        $amount = $request['quote']['totalCost'] * 100;

        try {
            $paymentIntent = $this->paymentGateway->createPaymentIntent($customerId, $amount, 'gbp', true);
            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function refundPayment(Request $request)
    {
        $chargeId = $request->input('charge_id');

        try {
            $refund = $this->paymentGateway->refundPayment($chargeId);
            return response()->json($refund);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

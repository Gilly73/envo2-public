<?php
namespace App\Service\Payment;

use Nette\Utils\Json;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentIntent;
use App\Models\Customer as CustomerModel;

class StripePaymentGateway implements PaymentGatewayInterface
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createCustomer($customer)
    {
        $customer = CustomerModel::find($customer['id']);
        
        if (!$customer->stripe_customer_id) {
            $stripeCustomer = Customer::create([
                'email' => $customer->email,
                'name'  => $customer->getFullNameAttribute(),
                'address'  => $customer->getStripeFormattedAddressAttribute(),
            ]);
            $customer->stripe_customer_id = $stripeCustomer->id;
            $customer->save();
        }
        
        return $customer->stripe_customer_id;
    }

    public function createPaymentIntent($customerId, $amount, $currency = 'gbp', $setupFutureUsage = false)
    {
        $paymentIntentData = [
            'amount'   => $amount,
            'currency' => $currency,
            'customer' => $customerId,
            'automatic_payment_methods' => ['enabled' => true],
            'confirm' => false,
            //'payment_method' => 'pm_card_visa',
            //'return_url' => 'https://frontend/customer-confirmation',
        ];

        if ($setupFutureUsage) {
            $paymentIntentData['setup_future_usage'] = 'off_session';
        }
        return PaymentIntent::create($paymentIntentData);
    }

    public function retrievePaymentIntentId(Json $data) : string
    {
        $clientSecret = isset($data['clientSecret']) && $data['clientSecret'] ? $data['clientSecret'] : null;   
        if (!$clientSecret) {
            throw new \Exception('Client secret is required');
        }
        $paymentIntent = PaymentIntent::retrieve($clientSecret);
        return $paymentIntent->id;
    }

    public function refundPayment($chargeId)
    {
        return \Stripe\Refund::create(['charge' => $chargeId]);
    }
}

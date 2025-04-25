<?php

namespace App\Repository;

use App\Repository\Interfaces\PaymentRepositoryInterface;
use App\Models\Payment;
class EloquentPaymentRepository extends EloquentBaseRepository implements PaymentRepositoryInterface
{
    public function __construct(Payment $payment)
    {
        parent::__construct($payment);
    }

    public function updatePayment(array $data): Payment
    {
        $payment = $this->model->find($data['id']);
        if ($payment) {
            $payment->update($data);
            return $payment;
        }
        throw new \Exception('Payment not found - updating payment');
    }


}

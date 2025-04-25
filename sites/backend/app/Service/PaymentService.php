<?php

namespace App\Service;

use App\Repository\Interfaces\PaymentRepositoryInterface;

class PaymentService
{
    protected $paymentRepository;

    public function __construct(PaymentRepositoryInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

}

<?php

namespace App\Repository\Interfaces;

interface PaymentRepositoryInterface extends BaseRepositoryInterface
{
    public function updatePayment(array $data);
}
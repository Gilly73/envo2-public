<?php

namespace App\Service;

use App\Repository\Interfaces\OrderRepositoryInterface;
use App\Repository\Interfaces\PaymentRepositoryInterface;
use App\Exceptions\OrderException;

class OrderService
{
    protected $orderRepository;
    protected $paymentRepository;

    public function __construct(OrderRepositoryInterface $orderRepository, PaymentRepositoryInterface $paymentRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->paymentRepository = $paymentRepository;
    }

    public function getOrder(int $id)
    {
        return $this->orderRepository->find($id);
    }

    public function saveOrder(array $data)
    {
        return $this->orderRepository->create($data);
    }

    public function savePayment(array $data)
    {
        return $this->paymentRepository->create($data);
    }

    public function updatePaymentWithOrderId($paymentId, $order)
    {
        try {
            $data = [
                'id' => $paymentId,
                'order_id' => $order->id,
            ];
            return $this->paymentRepository->updatePayment($data);
        } catch (\Exception $e) {
            throw new OrderException($e->getMessage(), 400);
        }
    }

   
}

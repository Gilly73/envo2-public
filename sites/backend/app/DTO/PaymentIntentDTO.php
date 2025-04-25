<?php

namespace App\DTO;

class PaymentIntentDTO
{
    public function __construct (
        public ?int $order_id = null,
        public int $customer_id,
        public ?string $payment_intent_id = null,
        public int $amount,
        public string $currency,
        public ?string $payment_method = null,
        public string $status
    ) {
        $this->currency = strtoupper($this->currency);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            order_id: $data['order_id'] ?? null,
            customer_id: $data['customer_id'],
            payment_intent_id: $data['payment_intent_id'] ?? null,
            amount: $data['amount'],
            currency: $data['currency'],
            payment_method: $data['payment_method'],
            status: $data['status']
        );
    }

    public function toArray(): array
    {
        return [
            'order_id' => $this->order_id,
            'customer_id' => $this->customer_id,
            'payment_intent_id' => $this->payment_intent_id,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'payment_method' => $this->payment_method,
            'status' => $this->status,
        ];
    }

  
}

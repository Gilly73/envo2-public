<?php

namespace App\DTO;

class OrderDTO
{
    public function __construct(
        public int $quote_id,
        public int $customer_id,
        public int $payment_id,
        public float $amount,
        public string $currency,
        public string $status,
        public ?int $id = null,
    ) {
        $this->currency = strtoupper($this->currency);
        $this->amount = round($this->amount, 2);
    }

    public function withId(int $id): self
    {
        $clone        = clone $this;
        $clone->id    = $id;
        return $clone;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            quote_id: $data['quote_id'],
            customer_id: $data['customer_id'],
            payment_id: $data['payment_id'],
            amount: $data['amount'],
            currency: $data['currency'],
            status: $data['status']
        );
    }

    public function toArray(): array
    {
        $data = [
            'quote_id' => $this->quote_id,
            'customer_id' => $this->customer_id,
            'payment_id' => $this->payment_id,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'status' => $this->status,
        ];

        if ($this->id !== null) {
            $data['id'] = $this->id;
        }
        return $data;
    }
}

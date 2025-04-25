<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $order = $this->resource['order'];
        $quote = $this->resource['quote'];

        return [
            'order_number' => $order->id,
            'discount' => $quote['discount']    ?? null,
            'description' => $quote['description'] ?? null,
            'total_cost' => number_format($order->amount, 2, '.', ','),
            'status' => $order->status,
            'currency' => symbol_from_currency_code($order->currency),
            'message' => 'Order processed successfully'
        ];
    }

}

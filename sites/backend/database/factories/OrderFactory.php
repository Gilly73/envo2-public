<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = \App\Models\Order::class;

    public function definition()
    {
        return [
            'customer_id' => '',
            'quote_id' => '',
            'country' => '',
            'status' => '', //delivered, beingBuilt, pending, waiting ? 
            'additional_info'
        ];
    }
}

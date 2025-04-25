<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = \App\Models\Customer::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'date_of_birth' => '2000-06-02',
            'email' => $this->faker->unique()->safeEmail(),
            'mobile' => $this->faker->phoneNumber(),
            'address_1' => $this->faker->streetAddress(),
            'address_2' => $this->faker->secondaryAddress(),
            'city' => $this->faker->city(),
            'county' => $this->faker->state(),
            'postcode' => $this->faker->postcode(),
            'country' => 'UK',
        ];
    }
}

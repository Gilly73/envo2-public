<?php

namespace Database\Factories\Couch;

use App\Models\Couch\Style;
use Illuminate\Database\Eloquent\Factories\Factory;

class StyleFactory extends Factory
{
    protected $model = Style::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'base_price' => $this->faker->randomFloat(2, 100, 1000),
            'couch_type_id' => $this->faker->numberBetween(1, 2),
        ];
    }
}

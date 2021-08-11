<?php

namespace Database\Factories;

use BristolSU\Support\Progress\ProgressHash;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProgressHashFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProgressHash::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'item_key' => sprintf('%s_%u', $this->faker->word, $this->faker->numberBetween(0, 100)),
            'hash' => $this->faker->sha1
        ];
    }
}

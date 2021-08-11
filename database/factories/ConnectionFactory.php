<?php

namespace Database\Factories;

use BristolSU\Support\Connection\Connection;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConnectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Connection::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->text,
            'user_id' => 1,
            'alias' => $this->faker->unique()->word,
            'settings' => [
                'foo' => 'bar',
                'baz' => 'que'
            ],
        ];
    }
}

<?php

namespace Database\Factories;

use BristolSU\Support\Completion\CompletionConditionInstance;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompletionConditionInstanceFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CompletionConditionInstance::class;

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
            'alias' => $this->faker->word,
            'settings' => [
                'foo' => $this->faker->word,
                'bar' => $this->faker->word,
                'baz' => $this->faker->word,
            ]
        ];
    }
}

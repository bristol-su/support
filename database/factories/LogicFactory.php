<?php

namespace Database\Factories;

use BristolSU\Support\Logic\Logic;
use Illuminate\Database\Eloquent\Factories\Factory;

class LogicFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Logic::class;

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
            'user_id' => fn () => \BristolSU\ControlDB\Models\User::factory()->create()->id()
        ];
    }
}

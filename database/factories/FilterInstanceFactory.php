<?php

namespace Database\Factories;

use BristolSU\Support\Filters\FilterInstance;
use Illuminate\Database\Eloquent\Factories\Factory;

class FilterInstanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FilterInstance::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'alias' => $this->faker->word,
            'name' => $this->faker->word,
            'settings' => $this->faker->randomElements(),
            'logic_id' => fn () => \BristolSU\Support\Logic\Logic::factory()->create()->id,
            'logic_type' => 'all_true'
        ];
    }
}

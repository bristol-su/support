<?php

namespace Database\Factories;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Progress\Handlers\Database\Models\Progress;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProgressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Progress::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'activity_instance_id' => fn () => ActivityInstance::factory()->create()->id,
            'complete' => $this->faker->boolean,
            'percentage' => $this->faker->numberBetween(0, 100),
            'timestamp' => $this->faker->dateTime
        ];
    }
}

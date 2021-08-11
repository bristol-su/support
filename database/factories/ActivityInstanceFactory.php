<?php

namespace Database\Factories;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityInstanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ActivityInstance::class;

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
            'activity_id' => fn () => Activity::factory()->create()->id,
            'resource_type' => 'user',
            'resource_id' => 1
        ];
    }

    public function user()
    {
        return $this->state(fn (array $attributes) => [
            'resource_type' => 'user'
        ]);
    }

    public function group()
    {
        return $this->state(fn (array $attributes) => [
            'resource_type' => 'group'
        ]);
    }

    public function role()
    {
        return $this->state(fn (array $attributes) => [
            'resource_type' => 'role'
        ]);
    }
}

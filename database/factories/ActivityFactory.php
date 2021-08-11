<?php

namespace Database\Factories;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Logic\Logic;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Activity::class;

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
            'activity_for' => 'user',
            'for_logic' => fn() => Logic::factory()->create()->id,
            'admin_logic' => fn() => Logic::factory()->create()->id,
            'type' => 'open',
            'start_date' => $this->faker->dateTimeInInterval('-1 year', '-5 days'),
            'end_date' => $this->faker->dateTimeInInterval('+5 days', '+1 year'),
            'enabled' => true,
            'user_id' => fn() => \BristolSU\ControlDB\Models\User::factory()->create()->id(),
            'image_url' => $this->faker->imageUrl()
        ];
    }

    public function user()
    {
        return $this->state(fn(array $attributes) => ['activity_for' => 'user']);
    }

    public function group()
    {
        return $this->state(fn(array $attributes) => ['activity_for' => 'group']);
    }

    public function alwaysActive()
    {
        return $this->state(fn(array $attributes) => ['start_date' => null, 'end_date' => null]);
    }

    public function inactive()
    {
        return $this->state(fn(array $attributes) => [
            'start_date' => $this->faker->dateTimeInInterval('-30 days', '-3 days'),
            'end_date' => $this->faker->dateTimeInInterval('-3 days', '-1 day')
        ]);
    }

}

<?php

namespace Database\Factories;

use BristolSU\Support\Action\History\ActionHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActionHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ActionHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'action_instance_id' => fn () => \BristolSU\Support\Action\ActionInstance::factory()->create()->id,
            'event_fields' => [],
            'settings' => [],
            'message' => $this->faker->sentence,
            'success' => $this->faker->boolean
        ];
    }
}

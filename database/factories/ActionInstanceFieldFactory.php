<?php

namespace Database\Factories;

use BristolSU\Support\Action\ActionInstance;
use BristolSU\Support\Action\ActionInstanceField;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActionInstanceFieldFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ActionInstanceField::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'action_value' => $this->faker->word,
            'action_field' => $this->faker->text,
            'action_instance_id' => fn() => ActionInstance::factory()->create()->id
        ];
    }
}

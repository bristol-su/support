<?php

namespace Database\Factories;

use BristolSU\Support\Action\ActionInstance;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActionInstanceFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ActionInstance::class;

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
            'event' => $this->faker->word,
            'action' => $this->faker->word,
            'module_instance_id' => fn() => ModuleInstance::factory()->create()->id,
            'user_id' => fn() => \BristolSU\ControlDB\Models\User::factory()->create()->id()
        ];
    }
}

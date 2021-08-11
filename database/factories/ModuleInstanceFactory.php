<?php

namespace Database\Factories;

use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\ModuleInstance\ModuleInstanceGrouping;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModuleInstanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ModuleInstance::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'alias' => $this->faker->word,
            'activity_id' => fn () => Activity::factory()->create()->id,
            'name' => $this->faker->word,
            'description' => $this->faker->text,
            'active' => fn () => Logic::factory()->create()->id,
            'visible' => fn () => Logic::factory()->create()->id,
            'mandatory' => fn () => Logic::factory()->create()->id,
            'completion_condition_instance_id' => null,
            'enabled' => true,
            'user_id' => fn () => User::factory()->create()->id(),
            'order' => null,
            'grouping_id' => fn () => ModuleInstanceGrouping::factory()->create()->id,
            'image_url' => $this->faker->imageUrl()
        ];
    }
}

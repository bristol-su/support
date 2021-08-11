<?php

namespace Database\Factories;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ModuleInstance\ModuleInstanceGrouping;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModuleInstanceGroupingFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ModuleInstanceGrouping::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'heading' => join(' ', $this->faker->words()),
            'order' => null,
            'activity_id' => fn() => Activity::factory()->create()->id
        ];
    }
}

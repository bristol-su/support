<?php

namespace Database\Factories;

use BristolSU\Support\Progress\Handlers\Database\Models\ModuleInstanceProgress;
use BristolSU\Support\Progress\Handlers\Database\Models\Progress;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModuleInstanceProgressFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ModuleInstanceProgress::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'module_instance_id' => fn() =>\BristolSU\Support\ModuleInstance\ModuleInstance::factory()->create()->id,
            'progress_id' => fn() => Progress::factory()->create()->id,
            'mandatory' => $this->faker->boolean,
            'complete' => $this->faker->boolean,
            'percentage' => $this->faker->numberBetween(0, 100),
            'active' => $this->faker->boolean,
            'visible' => $this->faker->boolean
        ];
    }
}

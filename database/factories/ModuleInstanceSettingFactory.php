<?php

namespace Database\Factories;

use BristolSU\Support\ModuleInstance\Settings\ModuleInstanceSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModuleInstanceSettingFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ModuleInstanceSetting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'key' => $this->faker->word,
            'value' => $this->faker->word,
            'module_instance_id' => fn() => \BristolSU\Support\ModuleInstance\ModuleInstance::factory()->create()->id,
            'encoded' => false
        ];
    }
}

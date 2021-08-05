<?php

namespace Database\Factories;

use BristolSU\Support\Connection\Connection;
use BristolSU\Support\ModuleInstance\Connection\ModuleInstanceService;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModuleInstanceServiceFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ModuleInstanceService::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'service' => $this->faker->word,
            'connection_id' => fn() =>Connection::factory()->create()->id,
            'module_instance_id' => fn() => ModuleInstance::factory()->create()->id
        ];
    }
}

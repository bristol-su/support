<?php

namespace Database\Factories;

use BristolSU\Support\Permissions\Models\ModuleInstancePermission;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModuleInstancePermissionFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ModuleInstancePermission::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'ability' => '',
            'logic_id' => fn() => \BristolSU\Support\Logic\Logic::factory()->create()->id,
            'module_instance_id' => fn() => \BristolSU\Support\ModuleInstance\ModuleInstance::factory()->create()->id
        ];
    }
}

<?php

namespace Database\Factories;

use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\Role;
use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Permissions\Models\ModelPermission;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModelPermissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ModelPermission::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'ability' => $this->faker->word,
            'model' => 'user',
            'model_id' => fn () => User::factory()->create()->id(),
            'result' => $this->faker->boolean,
            'module_instance_id' => null
        ];
    }

    public function user()
    {
        return $this->state(fn (array $attributes) => [
            'model' => 'user',
            'model_id' => fn () => User::factory()->create()->id()
        ]);
    }

    public function group()
    {
        return $this->state(fn (array $attributes) => [
            'model' => 'group',
            'model_id' => fn () => Group::factory()->create()->id()
        ]);
    }

    public function role()
    {
        return $this->state(fn (array $attributes) => [
            'model' => 'role',
            'model_id' => fn () => Role::factory()->create()->id()
        ]);
    }

    public function logic()
    {
        return $this->state(fn (array $attributes) => [
            'model' => 'logic',
            'model_id' => fn () => Logic::factory()->create()->id
        ]);
    }

    public function module()
    {
        return $this->state(fn (array $attributes) => [
            'module_instance_id' => fn () => ModuleInstance::factory()->create()->id
        ]);
    }

    public function true()
    {
        return $this->state(fn (array $attributes) => ['result' => true]);
    }

    public function false()
    {
        return $this->state(fn (array $attributes) => ['result' => false]);
    }
}

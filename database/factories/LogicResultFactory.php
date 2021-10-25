<?php

namespace Database\Factories;

use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\Role;
use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Action\History\ActionHistory;
use BristolSU\Support\Logic\DatabaseDecorator\LogicResult;
use BristolSU\Support\Logic\Logic;
use Illuminate\Database\Eloquent\Factories\Factory;

class LogicResultFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LogicResult::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'result' => $this->faker->boolean,
            'logic_id' => fn() => Logic::factory()->create(),
            'user_id' => fn() => User::factory()->create(),
            'group_id' => null,
            'role_id' => null
        ];
    }

    public function forLogic(Logic $logic)
    {
        return $this->state(fn(array $attributes) => [
            'logic_id' => $logic->id
        ]);
    }

    public function forUser(User $user)
    {
        return $this->state(fn(array $attributes) => [
            'user_id' => $user->id
        ]);
    }

    public function forGroup(?Group $group = null)
    {
        return $this->state(fn(array $attributes) => [
            'group_id' => ($group === null ? Group::factory()->create() : $group)->id()
        ]);
    }

    public function forRole(?Role $role = null)
    {
        return $this->state(fn(array $attributes) => [
            'role_id' => ($role === null ? $role = Role::factory()->create() : $role)->id(),
            'group_id' => $role->groupId()
        ]);
    }

    public function passing()
    {
        return $this->state(fn(array $attributes) => [
            'result' => true
        ]);
    }

    public function rejecting()
    {
        return $this->state(fn(array $attributes) => [
            'result' => false
        ]);
    }
}

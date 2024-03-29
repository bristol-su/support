<?php

namespace BristolSU\Support\Testing;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Authentication\Contracts\Authentication;

/**
 * Trait for aiding interactions with the user/group/role system.
 */
trait HandlesAuthentication
{
    /**
     * Create a new control user.
     *
     * @param array $attributes Attributes to use for the user
     * @return User
     */
    public function newUser($attributes = [])
    {
        return \BristolSU\ControlDB\Models\User::factory()->create($attributes);
    }

    /**
     * Create a new control group.
     *
     * @param array $attributes Attributes to use for the group
     * @return Group
     */
    public function newGroup($attributes = [])
    {
        return \BristolSU\ControlDB\Models\Group::factory()->create($attributes);
    }

    /**
     * Create a new control role.
     *
     * @param array $attributes Attributes to use for the role
     * @return Role
     */
    public function newRole($attributes = [])
    {
        return \BristolSU\ControlDB\Models\Role::factory()->create($attributes);
    }

    /**
     * Set a group.
     *
     * @param Group $group
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function beGroup(Group $group)
    {
        app()->make(Authentication::class)->setGroup($group);
    }

    /**
     * Set a role.
     *
     * @param Role $role
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function beRole(Role $role)
    {
        app()->make(Authentication::class)->setRole($role);
    }

    /**
     * Set a user.
     *
     * @param User $user
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function beUser(User $user)
    {
        app()->make(Authentication::class)->setUser($user);
    }
}

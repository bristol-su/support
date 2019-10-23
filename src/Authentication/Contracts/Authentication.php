<?php

namespace BristolSU\Support\Authentication\Contracts;

use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use \BristolSU\Support\Control\Contracts\Models\User;

/**
 * Interface Authentication
 * @package BristolSU\Support\Authentication\Contracts
 */
interface Authentication
{
    public function getGroup();

    public function getRole();

    public function getUser();

    /**
     * @param Group $group
     * @return mixed
     */
    public function setGroup(Group $group);

    /**
     * @param Role $role
     * @return mixed
     */
    public function setRole(Role $role);

    /**
     * @param User $user
     * @return mixed
     */
    public function setUser(User $user);
}

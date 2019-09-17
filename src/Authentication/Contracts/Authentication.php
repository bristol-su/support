<?php

namespace BristolSU\Support\Authentication\Contracts;

use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use BristolSU\Support\User\User;

interface Authentication
{
    public function getGroup();

    public function getRole();

    public function getUser();

    public function setGroup(Group $group);

    public function setRole(Role $role);

    public function setUser(User $user);
}

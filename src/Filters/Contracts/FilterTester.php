<?php


namespace BristolSU\Support\Filters\Contracts;


use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use BristolSU\Support\User\User;

interface FilterTester
{
    public function evaluate(FilterInstance $filterInstance, $userModel = null, $groupModel = null, $roleModel = null): bool;

    public function setUser(User $user);

    public function setGroup(Group $group);

    public function setRole(Role $role);

}

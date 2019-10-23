<?php


namespace BristolSU\Support\Filters\Contracts;


use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use \BristolSU\Support\Control\Contracts\Models\User;

/**
 * Interface FilterTester
 * @package BristolSU\Support\Filters\Contracts
 */
interface FilterTester
{
    /**
     * @param FilterInstance $filterInstance
     * @param null $userModel
     * @param null $groupModel
     * @param null $roleModel
     * @return bool
     */
    public function evaluate(FilterInstance $filterInstance, $userModel = null, $groupModel = null, $roleModel = null): bool;

    /**
     * @param User $user
     * @return mixed
     */
    public function setUser(User $user);

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

}

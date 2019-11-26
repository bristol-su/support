<?php


namespace BristolSU\Support\Permissions\Contracts;


use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use BristolSU\Support\Control\Contracts\Models\User;
use BristolSU\Support\Permissions\Contracts\Models\Permission;

/**
 * Class Tester
 * @package BristolSU\Support\Permissions\Contracts\Testers
 */
abstract class Tester
{

    /**
     * @var Tester
     */
    private $successor = null;
    

    /**
     * @param Tester|null $tester
     */
    public function setNext(?Tester $tester = null)
    {
        $this->successor = $tester;
    }

    public function handle(Permission $permission, ?User $user, ?Group $group, ?Role $role)
    {
        $result = $this->can($permission, $user, $group, $role);
        if($result === null && $this->successor !== null) {
            return $this->successor->handle($permission, $user, $group, $role);
        }
        return $result;
    }

    /**
     * Do the given models have the ability?
     * 
     * @param Permission $permission
     * @param User|null $user
     * @param Group|null $group
     * @param Role|null $role
     * @return bool|null
     */
    abstract public function can(Permission $permission, ?User $user, ?Group $group, ?Role $role): ?bool;
}

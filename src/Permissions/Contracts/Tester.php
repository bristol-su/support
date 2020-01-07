<?php


namespace BristolSU\Support\Permissions\Contracts;


use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Permissions\Contracts\Models\Permission;

/**
 * Class to test a permission
 */
abstract class Tester
{

    /**
     * Holds the next tester in the chain.
     * 
     * @var Tester|null Null if no more testers found
     */
    private $successor = null;
    

    /**
     * Set the next tester in the chain
     * 
     * @param Tester|null $tester Next tester
     */
    public function setNext(?Tester $tester = null)
    {
        $this->successor = $tester;
    }

    /**
     * Check if the tester has a result for the permission, or call the tester successor if not.
     * 
     * @param Permission $permission Permission to check 
     * @param User|null $user User to check if they have the permission
     * @param Group|null $group Group to check if they have the permission
     * @param Role|null $role Role to check if they have the permission
     * 
     * @return bool|null Own result, or the result of the successor if own result is null
     */
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
     * @param Permission $permission Permission to check
     * @param User|null $user User to check the permission against
     * @param Group|null $group Group to check the permission against
     * @param Role|null $role Role to check the permission against
     * 
     * @return bool|null Bool is the result of the permission check, or null if no result found.
     */
    abstract public function can(Permission $permission, ?User $user, ?Group $group, ?Role $role): ?bool;
}

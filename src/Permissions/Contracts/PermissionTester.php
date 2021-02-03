<?php


namespace BristolSU\Support\Permissions\Contracts;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;

/**
 * Test a permission.
 */
interface PermissionTester
{
    /**
     * Test if the currently authenticated user/group/role has the given ability.
     *
     * @param string $ability Ability of the permission
     * @return bool
     */
    public function evaluate(string $ability): bool;

    /**
     * Test if the given set of credentials have a given ability.
     *
     * @param string $ability Ability of the permission
     * @param User|null $userModel User to test the permission against
     * @param Group|null $group Group to test the permission against
     * @param Role|null $role Role to test the permission against
     * @return bool Do the given credentials have the permission?
     */
    public function evaluateFor(string $ability, ?User $userModel, ?Group $group, ?Role $role): bool;
    
    /**
     * Register a new permission tester.
     *
     * @param Tester $tester Implementation of the permission tester
     */
    public function register(Tester $tester);
}

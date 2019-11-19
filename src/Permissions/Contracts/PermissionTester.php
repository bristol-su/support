<?php


namespace BristolSU\Support\Permissions\Contracts;


use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use BristolSU\Support\Control\Contracts\Models\User;
use BristolSU\Support\Permissions\Contracts\Testers\Tester;

/**
 * Interface PermissionTester
 * @package BristolSU\Support\Permissions\Contracts
 */
interface PermissionTester
{

    /**
     * Test if the currently authenticated user has the given ability
     * 
     * @param string $ability
     * @return bool
     */
    public function evaluate(string $ability): bool;

    /**
     * Test if the given set of credentials have a given ability
     * @param string $ability
     * @param User|null $userModel
     * @param Group|null $group
     * @param Role|null $role
     * @return bool
     */
    public function evaluateFor(string $ability, ?User $userModel, ?Group $group, ?Role $role): bool;
    
    /**
     * @param Tester $tester
     * @return mixed
     */
    public function register(Tester $tester);

}

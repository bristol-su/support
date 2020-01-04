<?php


namespace BristolSU\Support\Permissions\Contracts;


use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;

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

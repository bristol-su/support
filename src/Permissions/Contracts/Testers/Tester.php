<?php


namespace BristolSU\Support\Permissions\Contracts\Testers;


use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use BristolSU\Support\Control\Contracts\Models\User;

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
     * @var Role|null
     */
    private $role;
    /**
     * @var User|null
     */
    private $user;
    /**
     * @var Group|null
     */
    private $group;

    /**
     * @param Tester|null $tester
     */
    public function setNext(?Tester $tester = null)
    {
        $this->successor = $tester;
    }

    /**
     * @param string $ability
     * @return bool|null
     */
    public function next(string $ability, ?User $user, ?Group $group, ?Role $role)
    {
        if($this->successor === null) {
            return null;
        }
        return $this->successor->can($ability, $user, $group, $role);
    }

    /**
     * @param string $ability
     * @return bool|null
     */
    abstract public function can(string $ability, ?User $user, ?Group $group, ?Role $role): ?bool;
}

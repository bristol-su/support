<?php

namespace BristolSU\Support\Permissions;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Permissions\Contracts\Models\Permission;
use BristolSU\Support\Permissions\Contracts\PermissionRepository as PermissionRepositoryContract;
use BristolSU\Support\Permissions\Contracts\PermissionTester as PermissionTesterContract;
use BristolSU\Support\Permissions\Contracts\Tester;
use Exception;

/**
 * Test if credentials have permissions.
 */
class PermissionTester implements PermissionTesterContract
{
    /**
     * @var Authentication
     */
    private Authentication $authentication;

    /**
     * @var PermissionRepositoryContract
     */
    private PermissionRepositoryContract $permissionRepositoryContract;

    public function __construct(Authentication $authentication, PermissionRepositoryContract $permissionRepositoryContract)
    {
        $this->authentication = $authentication;
        $this->permissionRepositoryContract = $permissionRepositoryContract;
    }

    /**
     * Holds the testers to test the permission with.
     *
     * @var Tester[]
     */
    private $testers = [];

    /**
     * Evaluate a permission using the currently authenticated credentials.
     *
     * @param string $ability Ability to test
     * @throws Exception If no testers are registered
     * @return bool If the permission is owned by the logged in credentials
     */
    public function evaluate(string $ability): bool
    {
        $result = $this->evaluateFor($ability, $this->authentication->getUser(), $this->authentication->getGroup(), $this->authentication->getRole());

        return ($result ?? false);
    }

    /**
     * Evaluate a permission for the given credentials.
     *
     * @param string $ability Ability to test
     * @param User|null $user User to test the ability on
     * @param Group|null $group Group to test the ability on
     * @param Role|null $role Role to test the ability on
     * @throws Exception If no testers are registered
     * @return bool If the permission is owned
     */
    public function evaluateFor(string $ability, ?User $user = null, ?Group $group = null, ?Role $role = null): bool
    {
        $tester = $this->getChain();

        return ($tester->handle($this->getPermission($ability), $user, $group, $role) ?? false);
    }

    /**
     * Gets the tester chain.
     *
     * This returns the first registered tester. This tester will have a successor of the first tester, which in turn
     * will have a successor of the second tester etc.
     *
     * @throws Exception If no testers are registered
     * @return Tester First registered tester, with the successor chain set
     */
    public function getChain()
    {
        if (count($this->testers) === 0) {
            throw new Exception('No testers registered');
        }
        $testers = $this->testers;
        for ($i = 0; $i < (count($testers) - 1); $i++) {
            $testers[$i]->setNext($testers[$i + 1]);
        }

        return $testers[0];
    }

    /**
     * Get a permission from the ability.
     *
     * @param string $ability Ability of the permission
     * @return Permission
     */
    private function getPermission(string $ability): Permission
    {
        return $this->permissionRepositoryContract->get($ability);
    }

    /**
     * Register a new tester.
     *
     * @param Tester $tester Tester to register
     * @param null $position Position to insert the tester into. i.e. 0 will put the tester first.
     */
    public function register(Tester $tester, $position = null)
    {
        if ($position === null) {
            $this->testers[] = $tester;
        } else {
            array_splice($this->testers, $position, 0, [$tester]);
        }
    }
}

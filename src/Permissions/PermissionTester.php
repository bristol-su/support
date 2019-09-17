<?php

namespace BristolSU\Support\Permissions;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\User\Contracts\UserAuthentication;
use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Permissions\Contracts\Models\Permission;
use BristolSU\Support\Permissions\Contracts\PermissionRepository as PermissionRepositoryContract;
use BristolSU\Support\Permissions\Contracts\PermissionTester as PermissionTesterContract;
use BristolSU\Support\Permissions\Contracts\Tester;
use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use Exception;

/**
 * Test if credentials have permissions
 */
class PermissionTester implements PermissionTesterContract
{

    /**
     * Holds the testers to test the permission with
     *
     * @var Tester[]
     */
    private $testers = [];

    /**
     * Evaluate a permission using the currently authenticated credentials.
     *
     * @param string $ability Ability to test
     * @return bool If the permission is owned by the logged in credentials
     * @throws Exception If no testers are registered
     */
    public function evaluate(string $ability): bool
    {
        /*
         * We always need to pass a user in. This is always possible, since you will always be logged into a database user.
         * By default, we take from authentication. If this is null, we take from the database user
         */
        $user = app(Authentication::class)->getUser();
        if ($user === null && ($dbUser = app(UserAuthentication::class)->getUser()) !== null) {
            $user = app(UserRepository::class)->getById($dbUser->control_id);
        }

        $result = $this->evaluateFor($ability, $user, app(Authentication::class)->getGroup(), app(Authentication::class)->getRole());
        return ($result ?? false);
    }

    /**
     * Evaluate a permission for the given credentials.
     *
     * @param string $ability Ability to test
     * @param User|null $user User to test the ability on
     * @param Group|null $group Group to test the ability on
     * @param Role|null $role Role to test the ability on
     * @return bool If the permission is owned
     * @throws Exception If no testers are registered
     */
    public function evaluateFor(string $ability, ?User $user = null, ?Group $group = null, ?Role $role = null): bool
    {
        $tester = $this->getChain();
        return ($tester->handle($this->getPermission($ability), $user, $group, $role) ?? false);
    }

    /**
     * Gets the tester chain
     *
     * This returns the first registered tester. This tester will have a successor of the first tester, which in turn
     * will have a successor of the second tester etc.
     *
     * @return Tester First registered tester, with the successor chain set
     * @throws Exception If no testers are registered
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
     * Get a permission from the ability
     * 
     * @param string $ability Ability of the permission
     * @return Permission 
     */
    private function getPermission(string $ability): Permission
    {
        return app(PermissionRepositoryContract::class)->get($ability);
    }

    /**
     * Register a new tester
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

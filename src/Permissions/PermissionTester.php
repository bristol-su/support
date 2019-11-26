<?php


namespace BristolSU\Support\Permissions;


use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use BristolSU\Support\Control\Contracts\Models\User;
use BristolSU\Support\Permissions\Contracts\Models\Permission;
use BristolSU\Support\Permissions\Contracts\PermissionRepository as PermissionRepositoryContract;
use BristolSU\Support\Permissions\Contracts\PermissionTester as PermissionTesterContract;
use BristolSU\Support\Permissions\Contracts\Testers\Tester;

/**
 * Class PermissionTester
 * @package BristolSU\Support\Permissions
 */
class PermissionTester implements PermissionTesterContract
{

    /**
     * @var Tester[]
     */
    private $testers = [];

    /**
     * @param string $ability
     * @return bool
     * @throws \Exception
     */
    public function evaluate(string $ability): bool
    {
        $tester = $this->getChain();
        $result = $tester->handle($this->getPermission($ability), app(Authentication::class)->getUser(), app(Authentication::class)->getGroup(), app(Authentication::class)->getRole());
        return ($result??false);
    }
    
    public function evaluateFor(string $ability, ?User $user = null, ?Group $group = null, ?Role $role = null): bool
    {
        $tester = $this->getChain();
        return ($tester->handle($this->getPermission($ability), $user, $group, $role)??false);
    }

    /**
     * @return Tester
     * @throws \Exception
     */
    public function getChain()
    {
        if(count($this->testers) === 0) {
            throw new \Exception('No testers registered');
        }
        $testers = $this->testers;
        for ($i = 0; $i < (count($testers) - 1); $i++) {
            $testers[$i]->setNext($testers[$i + 1]);
        }
        return $testers[0];
    }

    /**
     * @param Tester $tester
     */
    public function register(Tester $tester)
    {
        $this->testers[] = $tester;
    }

    public function getPermission(string $ability): Permission
    {
        return app(\BristolSU\Support\Permissions\Contracts\PermissionRepository::class)->get($ability);
    }
}

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
     * Evaluate a permission
     * 
     * @param string $ability
     * @return bool
     * @throws \Exception
     */
    public function evaluate(string $ability): bool
    {
        /**
         * We always need to pass a user in. This is always possible, since you will always be logged into a database user.
         * By default, we take from authentication. If this is null, we take from the database user
         */
        $user = app(Authentication::class)->getUser();
        if($user === null && ($dbUser = app(UserAuthentication::class)->getUser()) !== null) {
            $user = app(UserRepository::class)->getById($dbUser->control_id);
        };
        
        $result = $this->evaluateFor($ability, $user, app(Authentication::class)->getGroup(), app(Authentication::class)->getRole());
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
    public function register(Tester $tester, $position = null)
    {
	if($position === null) {
		$this->testers[] = $tester;
	} else {
		array_splice($this->testers, $position, 0, [$tester]);
	}
        
    }

    private function getPermission(string $ability): Permission
    {
        return app(PermissionRepositoryContract::class)->get($ability);
    }
}

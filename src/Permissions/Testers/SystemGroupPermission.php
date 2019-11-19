<?php

namespace BristolSU\Support\Permissions\Testers;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Permissions\Contracts\Testers\Tester;
use BristolSU\Support\Permissions\Models\ModelPermission;
use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use BristolSU\Support\Control\Contracts\Models\User;

/**
 * Class SystemGroupPermission
 * @package BristolSU\Support\Permissions\Testers
 */
class SystemGroupPermission extends Tester
{
    /**
     * @var Authentication
     */
    private $authentication;

    /**
     * SystemGroupPermission constructor.
     * @param Authentication $authentication
     */
    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    /**
     * @param string $ability
     * @return bool|null
     */
    public function can(string $ability, ?User $user, ?Group $group, ?Role $role): ?bool
    {
        $group = $this->authentication->getGroup();
        if($group === null) {
            return parent::next($ability);
        }
        $permissions = ModelPermission::group($group->id, $ability);
        if($permissions->count() === 0) {
            return parent::next($ability);
        }
        return $permissions->first()->result;
    }
}

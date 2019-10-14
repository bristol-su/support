<?php

namespace BristolSU\Support\Permissions\Testers;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Permissions\Contracts\Testers\Tester;
use BristolSU\Support\Permissions\Models\ModelPermission;

class SystemRolePermission extends Tester
{
    /**
     * @var Authentication
     */
    private $authentication;

    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    public function can(string $ability): ?bool
    {
        $role = $this->authentication->getRole();
        if($role === null) {
            return parent::next($ability);
        }
        $permissions = ModelPermission::role($role->id, $ability);
        if($permissions->count() === 0) {
            return parent::next($ability);
        }
        return $permissions->first()->result;
    }
}

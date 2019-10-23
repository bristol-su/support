<?php

namespace BristolSU\Support\Permissions\Testers;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Permissions\Contracts\Testers\Tester;
use BristolSU\Support\Permissions\Models\ModelPermission;

/**
 * Class SystemUserPermission
 * @package BristolSU\Support\Permissions\Testers
 */
class SystemUserPermission extends Tester
{
    /**
     * @var Authentication
     */
    private $authentication;

    /**
     * SystemUserPermission constructor.
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
    public function can(string $ability): ?bool
    {
        $user = $this->authentication->getUser();
        if($user === null) {
            return parent::next($ability);
        }
        $permissions = ModelPermission::user($user->id, $ability);
        if($permissions->count() === 0) {
            return parent::next($ability);
        }
        return $permissions->first()->result;
    }
}

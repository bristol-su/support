<?php


namespace BristolSU\Support\Permissions\Testers;


use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use BristolSU\Support\Control\Contracts\Models\User;
use BristolSU\Support\Permissions\Contracts\PermissionRepository;
use BristolSU\Support\Permissions\Contracts\Testers\Tester;

/**
 * Class CheckPermissionExists
 * @package BristolSU\Support\Permissions\Testers
 */
class CheckPermissionExists extends Tester
{

    /**
     * @var PermissionRepository
     */
    private $permissionRepository;

    /**
     * CheckPermissionExists constructor.
     * @param PermissionRepository $permissionRepository
     */
    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * @param string $ability
     * @return bool|null
     */
    public function can(string $ability, ?User $user, ?Group $group, ?Role $role): ?bool
    {
        try {
            $this->permissionRepository->get($ability);
        } catch (\Exception $e) {
            return false;
        }
        return parent::next($ability);
    }
}

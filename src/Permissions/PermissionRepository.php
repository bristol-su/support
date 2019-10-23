<?php


namespace BristolSU\Support\Permissions;


use BristolSU\Support\Permissions\Contracts\Models\Permission;
use BristolSU\Support\Permissions\Contracts\PermissionRepository as PermissionRepositoryContract;
use BristolSU\Support\Permissions\Contracts\PermissionStore as PermissionStoreContract;

/**
 * Class PermissionRepository
 * @package BristolSU\Support\Permissions
 */
class PermissionRepository implements PermissionRepositoryContract
{

    /**
     * @var PermissionStoreContract
     */
    private $permissionStore;

    /**
     * PermissionRepository constructor.
     * @param PermissionStoreContract $permissionStore
     */
    public function __construct(PermissionStoreContract $permissionStore)
    {
        $this->permissionStore = $permissionStore;
    }

    /**
     * @param string $ability
     * @return Permission
     */
    public function get(string $ability): Permission
    {
        return $this->permissionStore->get($ability);
    }

    /**
     * @param string $alias
     * @return array
     */
    public function forModule(string $alias): array
    {
        return collect($this->permissionStore->all())->filter(function(Permission $permission) use ($alias) {
            return $permission->getModuleAlias() === $alias;
        })->values()->toArray();
    }
}

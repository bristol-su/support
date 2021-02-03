<?php


namespace BristolSU\Support\Permissions;

use BristolSU\Support\Permissions\Contracts\Models\Permission;
use BristolSU\Support\Permissions\Contracts\PermissionRepository as PermissionRepositoryContract;
use BristolSU\Support\Permissions\Contracts\PermissionStore as PermissionStoreContract;

/**
 * Handle getting permissions from the permission store.
 */
class PermissionRepository implements PermissionRepositoryContract
{
    /**
     * Underlying permission store to retrieve permissions from.
     *
     * @var PermissionStoreContract
     */
    private $permissionStore;

    /**
     * @param PermissionStoreContract $permissionStore Permission store to retrieve permissions from
     */
    public function __construct(PermissionStoreContract $permissionStore)
    {
        $this->permissionStore = $permissionStore;
    }

    /**
     * Get a permission from the permission store.
     *
     * @param string $ability Ability of the permission
     * @return Permission
     */
    public function get(string $ability): Permission
    {
        return $this->permissionStore->get($ability);
    }

    /**
     * Get all permissions for a given module alias.
     *
     * @param string $alias Alias of the module
     * @return Permission[]
     */
    public function forModule(string $alias): array
    {
        return collect($this->permissionStore->all())->filter(function (Permission $permission) use ($alias) {
            return $permission->getModuleAlias() === $alias;
        })->values()->toArray();
    }

    /**
     * Get all permissions registered.
     *
     * @return Permission[]
     */
    public function all(): array
    {
        return $this->permissionStore->all();
    }
}

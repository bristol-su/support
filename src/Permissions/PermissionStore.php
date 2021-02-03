<?php

namespace BristolSU\Support\Permissions;

use BristolSU\Support\Permissions\Contracts\Models\Permission;
use BristolSU\Support\Permissions\Contracts\PermissionStore as PermissionStoreContract;

/**
 * Store all permissions in an array.
 */
class PermissionStore implements PermissionStoreContract
{
    /**
     * Holds the permissions registered.
     *
     * @var Permission[]
     */
    private $permissions = [];

    /**
     * Register a new site permission.
     *
     * @param string $ability Ability of the permission
     * @param string $name Name for the permission
     * @param string $description Description of the permission
     *
     */
    public function registerSitePermission(string $ability, string $name, string $description): void
    {
        $permission = resolve(Permission::class, [
            'ability' => $ability,
            'name' => $name,
            'description' => $description,
            'type' => 'global'
        ]);
        $this->registerPermission($permission);
    }

    /**
     * Register a permission model.
     *
     * @param Permission $permission Permission model to register
     */
    public function registerPermission(Permission $permission): void
    {
        $this->permissions[$permission->getAbility()] = $permission;
    }

    /**
     * Register a permission for a module.
     *
     * @param string $ability Ability of the permission
     * @param string $name Name for the permission
     * @param string $description Description for the permission
     * @param string $alias Alias of the module registering the permission
     * @param bool $admin If the permission is an admin permission $admin is true, or false for a participant permission
     */
    public function registerModulePermission(string $ability, string $name, string $description, string $alias, bool $admin = false): void
    {
        $permission = resolve(Permission::class, [
            'ability' => $ability,
            'name' => $name,
            'description' => $description,
            'type' => 'module',
            'alias' => $alias,
            'moduleType' => ($admin ? 'administrator' : 'participant')
        ]);
        $this->registerPermission($permission);
    }

    /**
     * Register a permission for a module.
     *
     * @param string $ability Ability of the permission
     * @param string $name Name for the permission
     * @param string $description Description for the permission
     * @param string $alias Alias of the module registering the permission
     * @param bool $admin If the permission is an admin permission $admin is true, or false for a participant permission
     */
    public function register(string $ability, string $name, string $description, string $alias, bool $admin = false): void
    {
        $this->registerModulePermission($ability, $name, $description, $alias, $admin);
    }

    /**
     * Get a permission by ability.
     *
     * @param string $ability Ability of the permission
     * @throws \Exception If the permission could not be found
     * @return Permission Permission with the given ability
     */
    public function get(string $ability): Permission
    {
        if (array_key_exists($ability, $this->permissions)) {
            return $this->permissions[$ability];
        }

        throw new \Exception('Permission ' . $ability . ' not registered');
    }

    /**
     * Get all registered permissions.
     *
     * @return Permission[]
     */
    public function all(): array
    {
        return $this->permissions;
    }
}

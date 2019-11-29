<?php


namespace BristolSU\Support\Permissions;


use BristolSU\Support\Permissions\Contracts\Models\Permission;
use BristolSU\Support\Permissions\Contracts\PermissionStore as PermissionStoreContract;

/**
 * Class PermissionStore
 * @package BristolSU\Support\Permissions
 */
class PermissionStore implements PermissionStoreContract
{

    /**
     * @var array
     */
    private $permissions = [];

    /**
     * @param string $ability
     * @param string $name
     * @param string $description
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
     * @param Permission $permission
     */
    public function registerPermission(Permission $permission): void
    {
        $this->permissions[$permission->getAbility()] = $permission;
    }

    /**
     * @param string $ability
     * @param string $name
     * @param string $description
     * @param string $alias
     * @param bool $admin
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
     * @param string $ability
     * @param string $name
     * @param string $description
     * @param string $alias
     * @param bool $admin
     */
    public function register(string $ability, string $name, string $description, string $alias, bool $admin = false): void
    {
        $this->registerModulePermission($ability, $name, $description, $alias, $admin);
    }

    /**
     * @param string $ability
     * @return Permission
     * @throws \Exception
     */
    public function get(string $ability): Permission
    {
        if (array_key_exists($ability, $this->permissions)) {
            return $this->permissions[$ability];
        }
        throw new \Exception('Permission '.$ability.' not registered');
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->permissions;
    }
}

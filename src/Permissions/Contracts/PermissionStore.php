<?php


namespace BristolSU\Support\Permissions\Contracts;


use BristolSU\Support\Permissions\Contracts\Models\Permission;

/**
 * Register and retrieve permissions
 */
interface PermissionStore
{
    /**
     * Register a global site permission
     *
     * @param string $ability Ability of the permission
     * @param string $name Name of the permission
     * @param string $description Description for the permission
     */
    public function registerSitePermission(string $ability, string $name, string $description): void;

    /**
     * Register a module permission.
     * 
     * Alias of the registerModulePermission function
     *
     * @param string $ability Ability of the permission
     * @param string $name Name of the permission
     * @param string $description Description of the permission
     * @param string $alias Alias of the module registering the permission
     * @param bool $admin Is the permission an admin permission? Defaults to false
     */
    public function register(string $ability, string $name, string $description, string $alias, bool $admin = false): void;


    /**
     * Register a module permission.
     *
     * @param string $ability Ability of the permission
     * @param string $name Name of the permission
     * @param string $description Description of the permission
     * @param string $alias Alias of the module registering the permission
     * @param bool $admin Is the permission an admin permission? Defaults to false
     */
    public function registerModulePermission(string $ability, string $name, string $description, string $alias, bool $admin = false): void;


    /**
     * Register a permission class
     *
     * @param Permission $permission Permission to register
     */
    public function registerPermission(Permission $permission): void;

    /**
     * Get a permission by its ability string
     * 
     * @param string $ability Ability string of the permission
     * @return Permission Permission with the given ability string
     */
    public function get(string $ability): Permission;

    /**
     * Get all registered permissions
     * 
     * @return Permission[]
     */
    public function all(): array;

}

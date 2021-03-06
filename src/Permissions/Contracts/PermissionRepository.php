<?php


namespace BristolSU\Support\Permissions\Contracts;

use BristolSU\Support\Permissions\Contracts\Models\Permission;

/**
 * Handle retrieving permissions.
 */
interface PermissionRepository
{
    /**
     * Get a permission by ability.
     *
     * @param string $ability Ability of the permission
     * @return Permission Permission model
     */
    public function get(string $ability): Permission;

    /**
     * Get all permissions for a module.
     *
     * @param string $alias Module alias to retrieve permissions from
     * @return Permission[]
     */
    public function forModule(string $alias): array;

    /**
     * Get all permissions registered.
     *
     * @return Permission[]
     */
    public function all(): array;
}

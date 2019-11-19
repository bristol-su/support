<?php


namespace BristolSU\Support\Permissions\Contracts;


use BristolSU\Support\Permissions\Contracts\Models\Permission;

/**
 * Interface PermissionRepository
 * @package BristolSU\Support\Permissions\Contracts
 */
interface PermissionRepository
{

    /**
     * @param string $ability
     * @return Permission
     */
    public function get(string $ability): Permission;

    /**
     * @param string $alias
     * @return array
     */
    public function forModule(string $alias): array;

    public function all(): array;
}

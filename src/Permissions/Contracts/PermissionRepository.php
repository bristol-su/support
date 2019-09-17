<?php


namespace BristolSU\Support\Permissions\Contracts;


use BristolSU\Support\Permissions\Contracts\Models\Permission;

interface PermissionRepository
{

    public function get(string $ability): Permission;

    public function forModule(string $alias): array;
}

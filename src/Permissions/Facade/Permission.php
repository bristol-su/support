<?php


namespace BristolSU\Support\Permissions\Facade;


use BristolSU\Support\Permissions\Contracts\Models\Permission as PermissionContract;
use BristolSU\Support\Permissions\Contracts\PermissionStore;
use Illuminate\Support\Facades\Facade;

/**
 * Facade for registering and retrieving a permission
 * 
 * @method static void register(string $ability, string $name, string $description, string $alias, bool $admin = false) Register a new module permission
 * @method static void registerSitePermission(string $ability, string $name, string $description) Register a new site permission
 * @method static void registerPermission(PermissionContract $permission) Register a new permission
 * @method static PermissionContract get(string $alias) Get a permission by alias
 */
class Permission extends Facade
{

    /**
     * Get the binding name for the store in the container
     * 
     * @return string Permission store contract
     */
    protected static function getFacadeAccessor()
    {
        return PermissionStore::class;
    }

}

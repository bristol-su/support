<?php

namespace BristolSU\Support\Module\Contracts;

/**
 * Manages module registration.
 */
interface ModuleManager
{
    /**
     * Register a new module.
     *
     * @param string $alias Alias of the module
     */
    public function register($alias);

    /**
     * Get all aliases which have been registered.
     *
     * @return array Array of module aliases
     */
    public function aliases();

    /**
     * Has the given module alias been registered?
     *
     * @param string $alias Alias to test
     * @return bool If the module has been registered
     */
    public function exists(string $alias): bool;
}

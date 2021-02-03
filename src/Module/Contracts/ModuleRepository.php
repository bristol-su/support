<?php


namespace BristolSU\Support\Module\Contracts;

/**
 * Class for retrieving modules.
 */
interface ModuleRepository
{
    /**
     * Get all modules registered.
     *
     * @return Module[]
     */
    public function all();

    /**
     * Get a module by alias.
     *
     * @param string $alias Alias of the module to find
     * @return Module|null Null if module not found, or the module otherwise
     */
    public function findByAlias($alias);
}

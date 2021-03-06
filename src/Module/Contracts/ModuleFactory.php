<?php


namespace BristolSU\Support\Module\Contracts;

/**
 * Create a module class.
 */
interface ModuleFactory
{
    /**
     * Create a module class from its alias.
     *
     * @param string $alias Alias of the module
     * @return Module Instantiated Module class representing the module with the given alias
     */
    public function fromAlias(string $alias): Module;
}

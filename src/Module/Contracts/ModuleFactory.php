<?php


namespace BristolSU\Support\Module\Contracts;


/**
 * Interface ModuleFactory
 * @package BristolSU\Support\Module\Contracts
 */
interface ModuleFactory
{
    /**
     * @param string $alias
     * @return Module
     */
    public function fromAlias(string $alias): Module;
}
<?php


namespace BristolSU\Support\ModuleInstance\Contracts;


/**
 * Represents a module instance
 */
interface ModuleInstance
{

    /**
     * Get the ID of the module instance
     * 
     * @return int
     */
    public function id();

    /**
     * Get the alias of the module the module instance uses
     * 
     * @return string
     */
    public function alias();

}

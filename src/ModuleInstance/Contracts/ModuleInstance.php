<?php


namespace BristolSU\Support\ModuleInstance\Contracts;

use BristolSU\ControlDB\Contracts\Models\User;

/**
 * Represents a module instance.
 */
interface ModuleInstance
{
    /**
     * Get the ID of the module instance.
     *
     * @return int
     */
    public function id();

    /**
     * Get the alias of the module the module instance uses.
     *
     * @return string
     */
    public function alias();

    /**
     * Get the user who created the module instance.
     *
     * @return User
     */
    public function user();
}

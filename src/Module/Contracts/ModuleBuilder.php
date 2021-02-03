<?php

namespace BristolSU\Support\Module\Contracts;

/**
 * A class with the knowledge of how to build a module class.
 */
interface ModuleBuilder
{
    /**
     * Initialise the module builder.
     *
     * @param string $alias Alias of the module
     */
    public function create(string $alias);

    /**
     * Set the alias of the module.
     *
     */
    public function setAlias();

    /**
     * Set the permissions of the module.
     *
     */
    public function setPermissions();

    /**
     * Set the name of the module.
     *
     */
    public function setName();

    /**
     * Set the description of the module.
     *
     */
    public function setDescription();

    /**
     * Set the settings of the module.
     *
     */
    public function setSettings();

    /**
     * Set the triggers of the module.
     *
     */
    public function setTriggers();

    /**
     * Set the completion conditions of the module.
     *
     */
    public function setCompletionConditions();

    /**
     * Set the services of the module.
     *
     */
    public function setServices();

    /**
     * Set what resource the module is for.
     *
     * This should be one of user, group or role
     *
     */
    public function setFor();
        
    /**
     * Get the built module.
     *
     * @return Module
     */
    public function getModule(): Module;
}

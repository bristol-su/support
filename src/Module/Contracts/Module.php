<?php

namespace BristolSU\Support\Module\Contracts;

use BristolSU\Support\Permissions\Contracts\Models\Permission;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * Represents a registered module in the framework.
 */
interface Module extends Arrayable, Jsonable
{
    /**
     * Set the alias of the module.
     *
     * @param string $alias Alias of the module
     */
    public function setAlias(string $alias): void;

    /**
     * Get the alias associated with the module.
     *
     * @return string
     */
    public function getAlias(): string;

    /**
     * Set the name of the module.
     *
     * @param string $name Name of the module
     */
    public function setName(string $name): void;
    
    /**
     * Get the name of the module.
     *
     * @return string Name of the module
     */
    public function getName(): string;

    /**
     * Set the completion conditions for the module.
     *
     * Conditions should be set as
     * [
     *      'name' => '',
     *      'description' => '',
     *      'options' => [],
     *      'alias' => ''
     * ]
     *
     * @param array $completionConditions
     */
    public function setCompletionConditions(array $completionConditions): void;

    /**
     * Get the completion conditions for the module.
     *
     * Return [
     *      'name' => '',
     *      'description' => '',
     *      'options' => [],
     *      'alias' => ''
     * ]
     *
     * @return array
     */
    public function getCompletionConditions(): array;
    
    /**
     * Set the description of the module.
     *
     * @param string $description Description of the module
     */
    public function setDescription(string $description): void;

    /**
     * Get the description of the module.
     *
     * @return string Description of the module
     */
    public function getDescription(): string;

    /**
     * Set the permissions for the module.
     *
     * @param Permission[] $permissions Set the permissions for the module
     */
    public function setPermissions(array $permissions): void;

    /**
     * Get the permissions for the module.
     *
     * @return Permission[]
     */
    public function getPermissions(): array;

    /**
     * Set the settings for the module.
     *
     * @param array $settings Form schema settings
     */
    public function setSettings(array $settings): void;

    /**
     * Get the settings for the module.
     *
     * @return array Form schema settings
     */
    public function getSettings(): array;

    /**
     * Set the triggers for the module.
     *
     * [
     *      'name' => 'Event Name',
     *      'description' => 'Event Description',
     *      'event' => 'EventClassName'
     * ]
     *
     * @param array $triggers Triggers for the module
     */
    public function setTriggers(array $triggers): void;

    /**
     * Get the triggers for the module.
     *
     * @return array Triggers
     */
    public function getTriggers(): array;

    /**
     * Set the services for the module.
     *
     * [
     *      'required' => ['typeform', 'facebook', ... ],
     *      'optional' => []
     * ]
     *
     * @param array $services Services for the module
     */
    public function setServices(array $services): void;

    /**
     * Get the services for the module.
     *
     * @return array
     */
    public function getServices(): array;

    /**
     * Set what resource the module is for. One of user, group or role.
     *
     * @param string $for One of user, group or role
     *
     */
    public function setFor(string $for = 'user');

    /**
     * Get what resource the module is for. One of user, group or role.
     *
     * @return string One of user, group or role
     */
    public function getFor(): string;
}

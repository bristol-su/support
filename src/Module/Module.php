<?php

namespace BristolSU\Support\Module;

use BristolSU\Support\Module\Contracts\Module as ModuleContract;
use BristolSU\Support\Permissions\Contracts\Models\Permission;

/**
 * Represents a module.
 */
class Module implements ModuleContract
{
    /**
     * Alias of the module.
     *
     * @var string
     */
    protected $alias;

    /**
     * Name of the module.
     *
     * @var string
     */
    protected $name;

    /**
     * Description of the module.
     *
     * @var string
     */
    protected $description;

    /**
     * Permissions associated with the module.
     *
     * @var Permission[]
     */
    protected $permissions;

    /**
     * Settings associated with the module.
     *
     * @var array Form schema
     */
    protected $settings;

    /**
     * Triggerable events fired by the module for use with actions.
     *
     * [
     *      'name' => 'Event Name',
     *      'description' => 'Event Description',
     *      'event' => 'EventClassName'
     * ]
     *
     * @var array
     */
    protected $triggers;

    /**
     * Services the module requires or can use.
     *
     * Held in the form
     * [
     *      'required' => ['typeform', 'facebook', ... ],
     *      'optional' => []
     * ]
     *
     * @var array
     */
    protected $services;

    /**
     * Who the module is for. One of user, group or role.
     *
     * @var string Who the module is for.
     */
    protected $for;
    
    /**
     * Completion conditions used by the module.
     *
     * [
     *      'name' => '',
     *      'description' => '',
     *      'options' => [],
     *      'alias' => ''
     * ]
     *
     * @var array
     */
    protected $completionConditions;

    /**
     * Set what resource the module is for. One of user, group or role.
     *
     * @param string $for One of user, group or role
     *
     */
    public function setFor(string $for = 'user')
    {
        $this->for = $for;
    }

    /**
     * Get what resource the module is for. One of user, group or role.
     *
     * @return string One of user, group or role
     */
    public function getFor(): string
    {
        return ($this->for ?? 'user');
    }

    /**
     * Return the module as a json representation.
     *
     * @return false|string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * Return the module as a json representation.
     *
     * @param int $options
     * @return false|string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Cast the module to an array.
     *
     * Returns an array of the form
     * [
     *      'alias' => 'module_alias',
     *      'name' => 'Module Name',
     *      'description' => 'Module Description',
     *      'permissions' => [],
     *      'settings' => [],
     *      'triggers' => [],
     *      'completionConditions' => [],
     *      'services' => []
     * ]
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'alias' => $this->getAlias(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'permissions' => $this->getPermissions(),
            'settings' => $this->getSettings(),
            'triggers' => $this->getTriggers(),
            'completionConditions' => $this->getCompletionConditions(),
            'services' => $this->getServices(),
            'for' => $this->getFor()
        ];
    }

    /**
     * Get the alias of the module.
     *
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * Set the alias of the module.
     *
     * @param string $alias Alias of the module
     */
    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    /**
     * Get the name of the module.
     *
     * @return string Name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the name of the module.
     *
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get the description for the module.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set the description for the module.
     *
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Get the permissions for the module.
     *
     * @return array
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * Set the permissions for the module.
     *
     * @param array $permissions
     */
    public function setPermissions(array $permissions): void
    {
        $this->permissions = $permissions;
    }

    /**
     * Get the settings for the module.
     *
     * @return array
     */
    public function getSettings(): array
    {
        return $this->settings;
    }

    /**
     * Set the settings for the module.
     *
     * @param array $settings
     */
    public function setSettings(array $settings): void
    {
        $this->settings = $settings;
    }

    /**
     * Get the triggers for the module.
     *
     * [
     *      'name' => 'Event Name',
     *      'description' => 'Event Description',
     *      'event' => 'EventClassName'
     * ]
     *
     * @return array
     */
    public function getTriggers(): array
    {
        return $this->triggers;
    }

    /**
     * Set the triggers for the module.
     *
     * [
     *      'name' => 'Event Name',
     *      'description' => 'Event Description',
     *      'event' => 'EventClassName'
     * ]
     *
     * @param array $triggers
     */
    public function setTriggers(array $triggers): void
    {
        $this->triggers = $triggers;
    }

    /**
     * Get the completion conditions used by the module.
     *
     * @return array
     */
    public function getCompletionConditions(): array
    {
        return $this->completionConditions;
    }

    /**
     * Set the completion conditions used by the module.
     *
     * @param array $completionConditions
     */
    public function setCompletionConditions(array $completionConditions): void
    {
        $this->completionConditions = $completionConditions;
    }

    /**
     * Get the services for the module.
     *
     * [
     *      'required' => ['typeform', 'facebook', ... ],
     *      'optional' => []
     * ]
     *
     * @return array
     */
    public function getServices(): array
    {
        return $this->services;
    }

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
    public function setServices(array $services): void
    {
        $this->services = $services;
    }
}

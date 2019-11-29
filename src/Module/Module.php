<?php

namespace BristolSU\Support\Module;

use BristolSU\Support\Module\Contracts\Module as ModuleContract;

/**
 * Class Module
 * @package BristolSU\Support\Module
 */
class Module implements ModuleContract
{

    /**
     * @var
     */
    protected $alias;
    /**
     * @var
     */
    protected $name;
    /**
     * @var
     */
    protected $description;
    /**
     * @var
     */
    protected $permissions;
    /**
     * @var
     */
    protected $settings;
    /**
     * @var
     */
    protected $triggers;

    /**
     * @var array
     */
    protected $completionConditions;
    
    /**
     * @return false|string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * @param int $options
     * @return false|string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
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
            'completionConditions' => $this->getCompletionConditions()
        ];
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     */
    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return array
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * @param array $permissions
     */
    public function setPermissions(array $permissions): void
    {
        $this->permissions = $permissions;
    }

    /**
     * @return array
     */
    public function getSettings(): array
    {
        return $this->settings;
    }

    /**
     * @param array $settings
     */
    public function setSettings(array $settings): void
    {
        $this->settings = $settings;
    }

    /**
     * @return array
     */
    public function getTriggers(): array
    {
        return $this->triggers;
    }

    /**
     * @param array $triggers
     */
    public function setTriggers(array $triggers): void
    {
        $this->triggers = $triggers;
    }

    /**
     * @param string $completionConditions
     */
    public function setCompletionConditions(array $completionConditions): void
    {
        $this->completionConditions = $completionConditions;
    }

    /**
     * @return string
     */
    public function getCompletionConditions(): array
    {
        return $this->completionConditions;
    }
}

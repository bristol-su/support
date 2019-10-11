<?php

namespace BristolSU\Support\Module;

use BristolSU\Support\Module\Contracts\Module as ModuleContract;

class Module implements ModuleContract
{

    protected $alias;
    protected $name;
    protected $description;
    protected $permissions;
    protected $settings;
    protected $triggers;

    public function __toString()
    {
        return $this->toJson();
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    public function toArray()
    {
        return [
            'alias' => $this->getAlias(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'permissions' => $this->getPermissions(),
            'settings' => $this->getSettings(),
            'triggers' => $this->getTriggers()
        ];
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function setPermissions(array $permissions): void
    {
        $this->permissions = $permissions;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function setSettings(array $settings): void
    {
        $this->settings = $settings;
    }

    public function getTriggers(): array
    {
        return $this->triggers;
    }

    public function setTriggers(array $triggers): void
    {
        $this->triggers = $triggers;
    }

}

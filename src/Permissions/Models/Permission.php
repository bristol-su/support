<?php


namespace BristolSU\Support\Permissions\Models;


use BristolSU\Support\Permissions\Contracts\Models\Permission as PermissionContract;

/**
 * Class Permission
 * @package BristolSU\Support\Permissions\Models
 */
class Permission implements PermissionContract
{

    /**
     * @var
     */
    private $ability;

    /**
     * @var
     */
    private $name;

    /**
     * @var
     */
    private $description;

    /**
     * @var
     */
    private $type;

    /**
     * @var
     */
    private $moduleAlias;

    /**
     * @var
     */
    private $moduleType;

    /**
     * Permission constructor.
     * @param string $ability
     * @param string $name
     * @param string $description
     * @param string $type
     * @param string $alias
     * @param string $moduleType
     */
    public function __construct(string $ability = '', string $name = '', string $description = '', string $type = 'global', string $alias = '', string $moduleType = '')
    {
        $this->setAbility($ability);
        $this->setName($name);
        $this->setDescription($description);
        $this->setType($type);
        $this->setModuleAlias($alias);
        $this->setModuleType($moduleType);
    }

    /**
     * @return string
     */
    public function getAbility(): string
    {
        return $this->ability;
    }

    /**
     * @param string $ability
     * @return mixed|void
     */
    public function setAbility(string $ability)
    {
        $this->ability = $ability;
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
     * @return mixed|void
     */
    public function setName(string $name)
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
     * @return mixed|void
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return mixed|void
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getModuleAlias(): string
    {
        return $this->moduleAlias;
    }

    /**
     * @param string $moduleAlias
     * @return mixed|void
     */
    public function setModuleAlias(string $moduleAlias)
    {
        $this->moduleAlias = $moduleAlias;
    }

    /**
     * @return string
     */
    public function getModuleType(): string
    {
        return $this->moduleType;
    }

    /**
     * @param string $moduleType
     * @return mixed|void
     */
    public function setModuleType(string $moduleType)
    {
        $this->moduleType = $moduleType;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'ability' => $this->getAbility(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'type' => $this->getType(),
            'alias' => $this->getModuleAlias(),
            'module_type' => $this->getModuleType()
        ];
    }
}

<?php


namespace BristolSU\Support\Permissions\Models;

use BristolSU\Support\Permissions\Contracts\Models\Permission as PermissionContract;

/**
 * Represents a permission.
 */
class Permission implements PermissionContract
{
    /**
     * The ability string of the permission.
     *
     * @var string
     */
    private $ability;

    /**
     * The name of the permission.
     *
     * @var string
     */
    private $name;

    /**
     * The description for the permission.
     *
     * @var string
     */
    private $description;

    /**
     * The type of the permission.
     *
     * This should either be module, if the permission is a module permission, or global if the permission is a site permission
     *
     * @var string module or global
     */
    private $type;

    /**
     * The module alias for the permission, if a module permission.
     *
     * @var string|null
     */
    private $moduleAlias;

    /**
     * The type of module permission if a module permission.
     *
     * This should either be 'administrator' or 'participant'
     *
     * @var string administrator or participant
     */
    private $moduleType;

    /**
     * Populate the permission model with the given attributes.
     *
     * @param string $ability The ability string of the permission.
     * @param string $name The name of the permission
     * @param string $description The description for the permission
     * @param string $type The type of the permission.
     * @param string|null $alias The module alias for the permission, if a module permission. Null otherwise
     * @param string|null $moduleType The type of module permission if a module permission. Null otherwise
     */
    public function __construct(string $ability = '', string $name = '', string $description = '', string $type = 'global', ?string $alias = null, ?string $moduleType = null)
    {
        $this->setAbility($ability);
        $this->setName($name);
        $this->setDescription($description);
        $this->setType($type);
        $this->setModuleAlias($alias);
        $this->setModuleType($moduleType);
    }

    /**
     * Get the ability string of the permission.
     *
     * @return string
     */
    public function getAbility(): string
    {
        return $this->ability;
    }

    /**
     * Set the ability string of the permission.
     *
     * @param string $ability New ability string
     */
    public function setAbility(string $ability)
    {
        $this->ability = $ability;
    }

    /**
     * Get the name of the permission.
     *
     * @return string Name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the name of the permission.
     *
     * @param string $name New name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get the description of the permission.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set the description of the permission.
     *
     * @param string $description New description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * Get the type of the permission.
     *
     * @return string module or global
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set the type of the permission.
     *
     * @param string $type One of global or module
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * Get the module alias of the module the permission belongs to.
     *
     * @return string|null Module alias, or null of a global permission
     */
    public function getModuleAlias(): ?string
    {
        return $this->moduleAlias;
    }

    /**
     * Set the module alias the permission belongs to.
     *
     * @param string|null $moduleAlias New module alias
     */
    public function setModuleAlias(?string $moduleAlias)
    {
        $this->moduleAlias = $moduleAlias;
    }

    /**
     * Get the type of module permission, either administrator or participant.
     *
     * @return string|null administrator or participant, or null if a global permission
     */
    public function getModuleType(): ?string
    {
        return $this->moduleType;
    }

    /**
     * Set the type of module permission, administrator or participant.
     *
     * @param null|string $moduleType Administrator or participant
     */
    public function setModuleType(?string $moduleType)
    {
        $this->moduleType = $moduleType;
    }

    /**
     * Get the permission represented as an array.
     *
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

    /**
     * Get the permission in a JSON representation.
     *
     * @param int $options See json_encode options for more information
     * @return false|string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Get the permission in a JSON representation.
     *
     * @return false|string
     */
    public function __toString()
    {
        return $this->toJson();
    }
}

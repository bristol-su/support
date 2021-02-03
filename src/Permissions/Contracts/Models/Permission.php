<?php

namespace BristolSU\Support\Permissions\Contracts\Models;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * Represents a permission registered in a Service Provider.
 */
interface Permission extends Arrayable, Jsonable
{
    /**
     * @param string $ability Ability string for the permission
     * @param string $name A name for the permission
     * @param string $description A description for the permission
     */
    public function __construct(string $ability = '', string $name = '', string $description = '');

    /**
     * Set the ability string of the permission.
     *
     * @param string $ability Ability String
     */
    public function setAbility(string $ability);

    /**
     * Set the name of the permission.
     *
     * @param string $name Name of the permission
     */
    public function setName(string $name);

    /**
     * Set the description of the permission.
     *
     * @param string $description Description of the permission
     */
    public function setDescription(string $description);

    /**
     * Set the type of permission.
     *
     * The type of the permission can either be 'global' for a system permission, or 'module' for a module permission
     *
     * @param string $type Type of the permission, either global or module
     */
    public function setType(string $type);

    /**
     * Set the module alias for the permission.
     *
     * If the permission is a module permission, the module alias is the module which has registered the permission
     *
     * @param null|string $moduleAlias Module that registers the permission
     */
    public function setModuleAlias(?string $moduleAlias);

    /**
     * Set the module type.
     *
     * If the permission is a module permission, this should either be 'administrator' or 'participant', depending on
     * if the module is an admin or a participant permission.
     *
     * @param null|string $moduleType
     * @return mixed
     */
    public function setModuleType(?string $moduleType);

    /**
     * Get the ability string for the permission.
     *
     * @return string Ability string
     */
    public function getAbility(): string;

    /**
     * Get the name of the permission.
     *
     * @return string Name of the permission
     */
    public function getName(): string;

    /**
     * Get the description of the permission.
     *
     * @return string Description of the permission
     */
    public function getDescription(): string;

    /**
     * Get the type of the permission.
     *
     * @return string Type of the permission
     */
    public function getType(): string;

    /**
     * Get the alias of the module for which the permission belongs.
     *
     * @return string|null Alias of the module. Null if not a module permission
     */
    public function getModuleAlias(): ?string;

    /**
     * Get the type of module permission.
     *
     * If the permission is an admin permission, this function should return 'administrator'. Otherwise, the function
     * will return 'participant'.
     *
     * @return string|null. administrator/participant. Null if not a module permission
     */
    public function getModuleType(): ?string;
}

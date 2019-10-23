<?php


namespace BristolSU\Support\Permissions\Contracts\Models;


use Illuminate\Contracts\Support\Arrayable;

/**
 * Interface Permission
 * @package BristolSU\Support\Permissions\Contracts\Models
 */
interface Permission extends Arrayable
{

    /**
     * Permission constructor.
     * @param string $ability
     * @param string $name
     * @param string $description
     */
    public function __construct(string $ability = '', string $name = '', string $description = '');

    /**
     * @param string $ability
     * @return mixed
     */
    public function setAbility(string $ability);

    /**
     * @param string $name
     * @return mixed
     */
    public function setName(string $name);

    /**
     * @param string $description
     * @return mixed
     */
    public function setDescription(string $description);

    /**
     * @param string $type
     * @return mixed
     */
    public function setType(string $type);

    /**
     * @param string $moduleAlias
     * @return mixed
     */
    public function setModuleAlias(string $moduleAlias);

    /**
     * @param string $moduleType
     * @return mixed
     */
    public function setModuleType(string $moduleType);

    /**
     * @return string
     */
    public function getAbility(): string;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function getModuleAlias(): string;

    /**
     * @return string
     */
    public function getModuleType(): string;
}

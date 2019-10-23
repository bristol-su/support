<?php

namespace BristolSU\Support\Module\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * Interface Module
 * @package BristolSU\Support\Module\Contracts
 */
interface Module extends Arrayable, Jsonable
{
    /**
     * @param string $alias
     */
    public function setAlias(string $alias): void;

    /**
     * @return string
     */
    public function getAlias(): string;

    /**
     * @param string $name
     */
    public function setName(string $name): void;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $description
     */
    public function setDescription(string $description): void;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @param array $permissions
     */
    public function setPermissions(array $permissions): void;

    /**
     * @return array
     */
    public function getPermissions(): array;

    /**
     * @param array $settings
     */
    public function setSettings(array $settings): void;

    /**
     * @return array
     */
    public function getSettings(): array;

    /**
     * @param array $triggers
     */
    public function setTriggers(array $triggers): void;

    /**
     * @return array
     */
    public function getTriggers(): array;
}
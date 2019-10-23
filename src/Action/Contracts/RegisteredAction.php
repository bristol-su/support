<?php

namespace BristolSU\Support\Action\Contracts;

/**
 * Interface RegisteredAction
 * @package BristolSU\Support\Action\Contracts
 */
interface RegisteredAction
{
    /**
     * @param $name
     * @return mixed
     */
    public function setName($name);

    public function getName();

    /**
     * @param $description
     * @return mixed
     */
    public function setDescription($description);

    public function getDescription();

    /**
     * @param $className
     * @return mixed
     */
    public function setClassName($className);

    public function getClassName();

    public function toArray();

    /**
     * @param int $options
     * @return mixed
     */
    public function toJson($options = 0);
}
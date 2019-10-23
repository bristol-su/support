<?php

namespace BristolSU\Support\Action\Contracts;

/**
 * Interface ActionManager
 * @package BristolSU\Support\Action\Contracts
 */
interface ActionManager
{

    /**
     * @param $class
     * @param $name
     * @param $description
     * @return mixed
     */
    public function registerAction($class, $name, $description);

    public function all();

    /**
     * @param $class
     * @return mixed
     */
    public function fromClass($class);
    
}
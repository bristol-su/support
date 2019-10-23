<?php

namespace BristolSU\Support\Action;

use BristolSU\Support\Action\Contracts\RegisteredAction as RegisteredActionContract;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * Class RegisteredAction
 * @package BristolSU\Support\Action
 */
class RegisteredAction implements Arrayable, Jsonable, RegisteredActionContract
{

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
    private $className;

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param $className
     */
    public function setClassName($className)
    {
        $this->className = $className;
    }

    /**
     * @return mixed
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param $parameters
     * @return RegisteredAction
     */
    public static function fromArray($parameters)
    {
        $registeredAction = new self;
        $registeredAction->setName($parameters['name']);
        $registeredAction->setDescription($parameters['description']);
        $registeredAction->setClassName($parameters['class']);
        return $registeredAction;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'class' => $this->getClassName()
        ];
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
     * @return false|string
     */
    public function __toString()
    {
        return $this->toJson();
    }
    
}
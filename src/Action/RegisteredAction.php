<?php

namespace BristolSU\Support\Action;

use BristolSU\Support\Action\Contracts\RegisteredAction as RegisteredActionContract;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class RegisteredAction implements Arrayable, Jsonable, RegisteredActionContract
{

    private $name;
    
    private $description;
    
    private $className;
    
    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setClassName($className)
    {
        $this->className = $className;
    }

    public function getClassName()
    {
        return $this->className;
    }
    
    public static function fromArray($parameters)
    {
        $registeredAction = new self;
        $registeredAction->setName($parameters['name']);
        $registeredAction->setDescription($parameters['description']);
        $registeredAction->setClassName($parameters['class']);
        return $registeredAction;
    }

    public function toArray()
    {
        return [
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'class' => $this->getClassName()
        ];
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    public function __toString()
    {
        return $this->toJson();
    }
    
}
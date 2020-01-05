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
     * Name of the action
     * 
     * @var string
     */
    private $name;

    /**
     * Description for the action
     * 
     * @var string
     */
    private $description;

    /**
     * Class name of the action
     * 
     * @var string
     */
    private $className;

    /**
     * Set the name of the action.
     * 
     * @param string $name Name of the action
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get the name of the action.
     *
     * @return string Name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the description of the action.
     * 
     * @param string $description Name of the description
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Get the description of the action.
     *
     * @return string Description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set the class name of the action.
     *
     * @param string $className Class of the action
     * @return void
     */
    public function setClassName(string $className): void
    {
        $this->className = $className;
    }

    /**
     * Get the class name of the action
     *
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * Create a RegisteredAction instance from a raw metadata array.
     *
     * Creates a RegisteredAction instance from an array of the form
     * [
     *      'name' => 'Action Name',
     *      'description' => 'Action Description',
     *      'class' => 'ClassName'
     * ]
     *
     * @param array $parameters The raw metadata of the action
     * @return \BristolSU\Support\Action\Contracts\RegisteredAction
     */
    public static function fromArray(array $parameters): RegisteredActionContract
    {
        $registeredAction = new self;
        $registeredAction->setName($parameters['name']);
        $registeredAction->setDescription($parameters['description']);
        $registeredAction->setClassName($parameters['class']);
        return $registeredAction;
    }

    /**
     * Transform the action to an array
     *
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
     * Transform the action to json
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Transform the action to json when casting class to a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }
    
}
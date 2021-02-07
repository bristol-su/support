<?php

namespace BristolSU\Support\Action\Contracts;

/**
 * Class to set and access action metadata.
 */
interface RegisteredAction
{
    /**
     * Set the name of the action.
     * @param string $name Name of the action
     */
    public function setName(string $name): void;

    /**
     * Get the name of the action.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Set the description of the action.
     *
     * @param string $description Description of the action
     */
    public function setDescription(string $description): void;

    /**
     * Get the action description.
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * Set the class name of the action.
     *
     * @param string $className Class of the action
     */
    public function setClassName(string $className): void;

    /**
     * Get the class name of the action.
     *
     * @return string
     */
    public function getClassName(): string;

    /**
     * Transform the action to an array.
     *
     * @return array
     */
    public function toArray();

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
     * @return RegisteredAction
     */
    public static function fromArray(array $parameters): RegisteredAction;
        
    /**
     * Transform the action to json.
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0);
}
